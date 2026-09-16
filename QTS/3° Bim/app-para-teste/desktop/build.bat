@echo off
REM ============================================================
REM  build.bat - compila e gera o jar executavel da aplicacao
REM  Rode:  build.bat
REM  Saida: target\biblioteca-desktop.jar
REM ============================================================
setlocal
cd /d "%~dp0"

where mvn >nul 2>nul
if errorlevel 1 goto SEM_MAVEN

echo Compilando e empacotando...
call mvn -q -DskipTests package
if errorlevel 1 goto FALHA

echo.
echo Pronto! Execute o run.bat ou:
echo     java -jar target\biblioteca-desktop.jar
goto FIM

:SEM_MAVEN
echo [ERRO] Maven nao encontrado. Instale o Maven e adicione ao PATH.
exit /b 1

:FALHA
echo [ERRO] Falha no build.
exit /b 1

:FIM
endlocal