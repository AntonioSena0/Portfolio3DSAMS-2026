@echo off
REM ============================================================
REM  run.bat - inicia a aplicacao desktop (Swing)
REM  Se o jar nao existir, compila antes (chama build.bat).
REM ============================================================
setlocal
cd /d "%~dp0"

if not exist "target\biblioteca-desktop.jar" (
    echo Jar nao encontrado. Compilando...
    call build.bat
    if errorlevel 1 exit /b 1
)

echo Iniciando a aplicacao...
java -jar target\biblioteca-desktop.jar
endlocal