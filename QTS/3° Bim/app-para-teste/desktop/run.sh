#!/usr/bin/env bash
# run.sh - inicia a aplicacao desktop (Linux/macOS)
set -e
cd "$(dirname "$0")"

if [ ! -f "target/biblioteca-desktop.jar" ]; then
    echo "Jar nao encontrado. Compilando..."
    ./build.sh
fi

echo "Iniciando a aplicacao..."
java -jar target/biblioteca-desktop.jar
