-- Esquema de la tabla de testimonios para el módulo TES
CREATE TABLE IF NOT EXISTS tes_testimonios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    carrera VARCHAR(100) NOT NULL,
    testimonio TEXT NOT NULL,
    estado VARCHAR(50) DEFAULT 'pendiente',
    motivo TEXT NULL,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP
);