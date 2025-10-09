#!/bin/bash

# filepath: /home/dev/dev/app/automation/fixpermission.sh

# Colores para mensajes
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${GREEN}Iniciando proceso de corrección de permisos...${NC}"

# Ir al directorio padre del script (donde está el proyecto)
echo -e "${YELLOW}Cambiando al directorio del proyecto...${NC}"
cd "$(dirname "$0")/.."
if [ $? -ne 0 ]; then
    echo -e "${RED}Error: No se pudo cambiar al directorio del proyecto${NC}"
    exit 1
fi

echo -e "${GREEN}Directorio actual: $(pwd)${NC}"

# Verificar si estamos en un repositorio git
if ! git rev-parse --git-dir > /dev/null 2>&1; then
    echo -e "${RED}Error: No se encuentra un repositorio git en $(pwd)${NC}"
    exit 1
fi

# Etapa todos los cambios
echo -e "${YELLOW}Etapando todos los cambios...${NC}"
git add .
if [ $? -ne 0 ]; then
    echo -e "${RED}Error al ejecutar git add${NC}"
    exit 1
fi

# Aplica chmod 777 recursivamente (requiere sudo)
echo -e "${YELLOW}Aplicando permisos 777 recursivamente...${NC}"
sudo chmod -R 777 .
if [ $? -ne 0 ]; then
    echo -e "${RED}Error al aplicar permisos${NC}"
    exit 1
fi

# Restaura el directorio de trabajo al último commit (deshace cambios de chmod)
echo -e "${YELLOW}Restaurando archivos al último commit...${NC}"
git restore .
if [ $? -ne 0 ]; then
    echo -e "${RED}Error al restaurar archivos${NC}"
    exit 1
fi

# Desetapa todos los cambios
echo -e "${YELLOW}Desetapando todos los cambios...${NC}"
git reset HEAD .
if [ $? -ne 0 ]; then
    echo -e "${RED}Error al resetear cambios${NC}"
    exit 1
fi

echo -e "${GREEN}¡Proceso de corrección de permisos completado exitosamente!${NC}"
echo -e "${GREEN}Los permisos han sido aplicados temporalmente sin afectar el repositorio.${NC}"