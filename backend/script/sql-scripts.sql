CREATE TABLE empresa (
    idempresa SERIAL PRIMARY KEY,
    identificacion VARCHAR(16) NOT NULL,
    razonsocial VARCHAR(256)
);

CREATE TABLE tipodocumento (
    idtipodocumento SERIAL PRIMARY KEY,
    description VARCHAR(256) NOT NULL
);

CREATE TABLE numeracion (
    idnumeracion SERIAL PRIMARY KEY,
    idtipodocumento INTEGER NOT NULL REFERENCES tipodocumento(idtipodocumento),
    idempresa INTEGER NOT NULL REFERENCES empresa(idempresa),
    prefijo VARCHAR(8) NOT NULL,
    consecutivoinicial INTEGER NOT NULL,
    consecutivofinal INTEGER NOT NULL,
    vigenciainicial DATE NOT NULL,
    vigenciafinal DATE NOT NULL
);

CREATE TABLE estado (
    idestado SERIAL PRIMARY KEY,
    description VARCHAR(256) NOT NULL,
    exitoso BOOLEAN NOT NULL
);

CREATE TABLE documento (
    iddocumento SERIAL PRIMARY KEY,
    idnumeracion INTEGER NOT NULL REFERENCES numeracion(idnumeracion),
    idestado INTEGER NOT NULL REFERENCES estado(idestado),
    fecha DATE NOT NULL,
    base DECIMAL NOT NULL,
    impuestos DECIMAL NOT NULL
);

ALTER TABLE numeracion
    ADD CONSTRAINT fk_numeracion_tipodocumento FOREIGN KEY (idtipodocumento) REFERENCES tipodocumento(idtipodocumento);

ALTER TABLE numeracion
    ADD CONSTRAINT fk_numeracion_empresa FOREIGN KEY (idempresa) REFERENCES empresa(idempresa);

ALTER TABLE documento
    ADD CONSTRAINT fk_documento_numeracion FOREIGN KEY (idnumeracion) REFERENCES numeracion(idnumeracion);

ALTER TABLE documento
    ADD CONSTRAINT fk_documento_estado FOREIGN KEY (idestado) REFERENCES estado(idestado);
    
    
INSERT INTO tipodocumento (description)
VALUES
    ('Factura'),
    ('Debito'),
    ('Credito');
    
    
INSERT INTO estado (description, exitoso)
VALUES
    ('Correcto', true),
    ('Validandose', true),
    ('Validado', true),
    ('Invalidado', false),
    ('Incorrecto', false),
    ('Caducado', false),
    ('Excede limite', false);
    

INSERT INTO empresa (identificacion, razonsocial)
VALUES
    ('NIT-1', 'Empresa 1'),
    ('NIT-2', 'Empresa 2'),
    ('NIT-3', 'Empresa 3');
    
    
INSERT INTO numeracion (idtipodocumento, idempresa, prefijo, consecutivoinicial, consecutivofinal, vigenciainicial, vigenciafinal)
VALUES
    (1, 1, 'FA-A', 1001, 1100, '2023-01-01', '2023-12-31'),
    (2, 1, 'ND-A', 2001, 2100, '2023-01-01', '2023-12-31'),
    (3, 1, 'NC-A', 3001, 3100, '2023-01-01', '2023-12-31'),
    (1, 1, 'FA-B', 1201, 1300, '2022-01-01', '2022-12-31'),
    (2, 1, 'ND-B', 2201, 2300, '2022-01-01', '2022-12-31'),
    (3, 1, 'NC-B', 3201, 3300, '2022-01-01', '2022-12-31');

INSERT INTO numeracion (idtipodocumento, idempresa, prefijo, consecutivoinicial, consecutivofinal, vigenciainicial, vigenciafinal)
VALUES
    (1, 2, 'FA-C', 1001, 1100, '2023-01-01', '2023-12-31'),
    (2, 2, 'ND-C', 2001, 2100, '2023-01-01', '2023-12-31'),
    (3, 2, 'NC-C', 3001, 3100, '2023-01-01', '2023-12-31'),
    (1, 2, 'FA-D', 1201, 1300, '2022-01-01', '2022-12-31'),
    (2, 2, 'ND-D', 2201, 2300, '2022-01-01', '2022-12-31'),
    (3, 2, 'NC-D', 3201, 3300, '2022-01-01', '2022-12-31');

INSERT INTO numeracion (idtipodocumento, idempresa, prefijo, consecutivoinicial, consecutivofinal, vigenciainicial, vigenciafinal)
VALUES
    (1, 3, 'FA-E', 1001, 1100, '2023-01-01', '2023-12-31'),
    (2, 3, 'ND-E', 2001, 2100, '2023-01-01', '2023-12-31'),
    (3, 3, 'NC-E', 3001, 3100, '2023-01-01', '2023-12-31'),
    (1, 3, 'FA-F', 1201, 1300, '2022-01-01', '2022-12-31'),
    (2, 3, 'ND-F', 2201, 2300, '2022-01-01', '2022-12-31'),
    (3, 3, 'NC-F', 3201, 3300, '2022-01-01', '2022-12-31');
    
    
INSERT INTO documento (idnumeracion, idestado, fecha, base, impuestos)
VALUES
    (1, 1, '2023-02-15', 1000.00, 190.00),
    (1, 2, '2023-03-20', 1500.00, 285.00),
    (1, 3, '2023-04-10', 800.00, 152.00),
    (1, 4, '2023-05-05', 1200.00, 228.00),
    (1, 5, '2023-06-12', 950.00, 180.50),
    (1, 6, '2023-07-01', 2000.00, 380.00);

INSERT INTO documento (idnumeracion, idestado, fecha, base, impuestos)
VALUES
    (2, 1, '2023-02-25', 500.00, 75.00),
    (2, 2, '2023-03-30', 800.00, 120.00),
    (2, 3, '2023-04-15', 300.00, 45.00),
    (2, 4, '2023-05-20', 1000.00, 150.00),
    (2, 5, '2023-06-25', 600.00, 90.00),
    (2, 6, '2023-07-10', 1200.00, 180.00);

INSERT INTO documento (idnumeracion, idestado, fecha, base, impuestos)
VALUES
    (3, 1, '2023-03-05', 200.00, 30.00),
    (3, 2, '2023-04-10', 300.00, 45.00),
    (3, 3, '2023-05-01', 150.00, 22.50),
    (3, 4, '2023-06-15', 500.00, 75.00),
    (3, 5, '2023-07-20', 250.00, 37.50),
    (3, 6, '2023-08-05', 400.00, 60.00);
    