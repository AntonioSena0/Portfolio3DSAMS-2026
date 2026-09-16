#!/usr/bin/env bash
# build.sh - compila e gera o jar executavel (Linux/macOS)
# Uso: ./build.sh
set -e
cd "$(dirname "$0")"

if ! command -v mvn >/dev/null 2>&1; then
    echo "[ERRO] Maven (mvn) nao encontrado. Instale o Maven e adicione ao PATH."
    exit 1
fi

echo "Compilando e empacotando..."
mvn -q -DskipTests package

echo "Pronto! Execute o run.sh ou:  java -jar target/biblioteca-desktop.jar"
