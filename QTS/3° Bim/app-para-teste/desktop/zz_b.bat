@echo off
setlocal
where mvn >nul 2>nul
if errorlevel 1 (
    echo ERRO_MAVEN
    exit /b 1
)
echo OK_APOS_IF
endlocal
