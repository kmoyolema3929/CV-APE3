USE cuartoa;

DROP TABLE IF EXISTS usuarios;

CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario VARCHAR(50) NOT NULL UNIQUE,
  clave VARCHAR(255) NOT NULL,
  intentos INT NOT NULL DEFAULT 0,
  bloqueado_hasta DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO usuarios (usuario, clave) VALUES ('docente', '$2y$10$blh2iSHZ1Tp8eup0bD1RGeNb76ElTopieBPKCw8317v7.2ep01sHi');