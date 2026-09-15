# StreamFlow — Mapeamento Objeto-Relacional (Hibernate 6 + MySQL 8.0)

## 1. Entidades (tabelas centrais)

### Customer (`customers`)
| Atributo | Coluna | Tipo Java |
|---|---|---|
| id | `id` | `Long` (PK) |
| name | `full_name` | `String` (trigger 2.4 normaliza) |
| email | `email` (UNIQUE) | `String` (trigger 2.4 normaliza) |
| cpf | `cpf` (UNIQUE) | `String` |
| state | `state_uf` | `String` |
| birthDate | `date_of_birth` | `LocalDate` (nunca exposta; ver item 3) |
| updatedAt | `updated_at` | `LocalDateTime` (só o trigger 2.4 escreve) |


```java
@NamedStoredProcedureQuery(
    name = "Customer.charge",
    procedureName = "realizar_cobranca_mensal",
    parameters = {
        @StoredProcedureParameter(name = "p_assinante_id", mode = ParameterMode.IN, type = Long.class),
        @StoredProcedureParameter(name = "p_valor_mensalidade", mode = ParameterMode.IN, type = BigDecimal.class),
        @StoredProcedureParameter(name = "p_novo_saldo", mode = ParameterMode.OUT, type = BigDecimal.class)
    }
)
@Entity @Table(name = "customers")
public class Customer {
    @Id @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;
    @Column(name = "full_name", nullable = false, length = 150)
    private String name;
    @Column(nullable = false, unique = true, length = 100)
    private String email;
    @Column(nullable = false, unique = true, length = 14)
    private String cpf;
    @Column(name = "state_uf", nullable = false, length = 2)
    private String state;
    @Column(name = "date_of_birth", nullable = false)
    private LocalDate birthDate;
    @Column(name = "updated_at", insertable = false, updatable = false)
    private LocalDateTime updatedAt;

    @OneToMany(mappedBy = "customer", cascade = CascadeType.ALL, orphanRemoval = true)
    private List<Profile> profiles = new ArrayList<>();

    public BigDecimal charge(EntityManager em, BigDecimal amount) {
        StoredProcedureQuery q = em.createNamedStoredProcedureQuery("Customer.charge");
        q.setParameter("p_assinante_id", this.id);
        q.setParameter("p_valor_mensalidade", amount);
        q.execute();
        return (BigDecimal) q.getOutputParameterValue("p_novo_saldo");
    }
}
```

### Profile (`profiles`)
| Atributo | Coluna | Tipo Java |
|---|---|---|
| id | `id` | `Long` (PK) |
| name | `display_name` | `String` |
| avatar | `avatar_color` | `String` |
| active | `is_active` | `Boolean` |
| customer | `id_customer` FK | `Customer` |

```java
@Entity @Table(name = "profiles")
public class Profile {
    @Id @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;
    @Column(name = "display_name", nullable = false, length = 100)
    private String name;
    @Column(name = "avatar_color", length = 7)
    private String avatar;
    @Column(name = "is_active")
    private Boolean active = true;
    @ManyToOne(fetch = FetchType.LAZY)
    @JoinColumn(name = "id_customer", nullable = false)
    private Customer customer;
}
```

### Video (`videos`)
| Atributo | Coluna | Tipo Java |
|---|---|---|
| id | `id` | `Long` (PK) |
| title | `title` | `String` |
| durationSeconds | `duration_seconds` | `Integer` |
| studioId | `id_studio` FK → `studios.id` | `Long` |
| categoryId | `id_category` FK → `categories.id` | `Long` (define movie vs. episode) |

```java
@Entity @Table(name = "videos")
@Inheritance(strategy = InheritanceType.SINGLE_TABLE)
@DiscriminatorFormula("(select c.category_type from categories c where c.id = id_category)")
public abstract class Video {
    @Id @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;
    private String title;
    @Column(name = "duration_seconds")
    private Integer durationSeconds;
    @Column(name = "id_studio", nullable = false, insertable = false, updatable = false)
    private Long studioId;
}

@Entity @DiscriminatorValue("FILM")
public class Movie extends Video { }

@Entity @DiscriminatorValue("SERIES")
public class Episode extends Video { }
```

### Playback (`play_logs`)
| Atributo | Coluna | Tipo Java |
|---|---|---|
| id | `id` | `Long` (PK) |
| ip | `ip_address` | `String` |
| playedAt | `play_timestamp` | `LocalDateTime` (hora do servidor) |
| profile | `id_profile` FK | `Profile` |
| video | `id_video` FK | `Video` |
| deviceTypeId | `id_device_type` FK | `Long` |

```java
@Entity @Table(name = "play_logs")
public class Playback {
    @Id @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;
    @Column(name = "ip_address", nullable = false, length = 45)
    private String ip;
    @Column(name = "play_timestamp", nullable = false, insertable = false, updatable = false)
    private LocalDateTime playedAt;
    @ManyToOne(fetch = FetchType.LAZY)
    @JoinColumn(name = "id_profile", nullable = false)
    private Profile profile;
    @ManyToOne(fetch = FetchType.LAZY)
    @JoinColumn(name = "id_video", nullable = false)
    private Video video;
    @Column(name = "id_device_type", nullable = false)
    private Long deviceTypeId;
}
```

## 2. Relações

**1:N (customer → profiles):** um `Customer` tem muitos `Profile` (`profiles.id_customer` com `ON DELETE CASCADE`); no código, `@OneToMany(mappedBy = "customer")` com `orphanRemoval`. O limite de 5 fica no trigger `limit_profiles`: o app trata o erro `45000`, nunca conta antes.

**Associação com atributos (playbacks):** não é N:N puro — `Playback` é entidade própria com PK e atributos (ip, momento, dispositivo). Cada lado enxerga 1:N (`Profile` 1:N `Playback`, `Video` 1:N `Playback`).

**Herança movie vs. episode:** `SINGLE_TABLE` em `videos` (código no item 1), com o tipo vindo de `categories.category_type` via `@DiscriminatorFormula`, sem mudar o schema.

## 3. Rotinas da Parte 1 via Hibernate (app delega, nunca recalcula)

| Rotina | Como o app chama |
|---|---|
| `realizar_cobranca_mensal` (IN id, IN valor, OUT novo) | `@NamedStoredProcedureQuery` + `getOutputParameterValue` (exemplo em Customer) |
| `registrar_reproducao` (INs + OUT id) | mesma técnica; OUT = `LAST_INSERT_ID()` |
| `gerar_faturamento_mensal` (IN competencia) | `CALL` em job agendado, sem `OUT` |
| `minutos_assistidos_por_produtora` | HQL: `select function('minutos_assistidos_por_produtora', :e, :c)` |
| `calcular_idade` | app lê a view `v_marketing_engagement` em vez de `customers` |

Erro `1644 (45000)` vira exceção de domínio no service; `commit` ou `rollback` conforme o `CALL`.