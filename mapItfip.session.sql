-- 2. RECREAR LA ESTRUCTURA (DDL)
-- Recrea la tabla con la clave primaria SMALLSERIAL
CREATE TABLE IF NOT EXISTS "TablaCarrera" (
  "IdCarrera" SMALLSERIAL PRIMARY KEY,
  "Carrera" VARCHAR(100) NOT NULL
);
