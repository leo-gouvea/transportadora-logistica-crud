-- =====================================
-- DADOS DE DEMONSTRAÇÃO
-- =====================================

-- USUÁRIO
INSERT INTO usuarios (nome, email, senha)
VALUES (
    'Administrador Demo',
    'admin@example.com',
    '$2y$10$m0e7Hh6ifoNz5SMXJzTxteLbUquY5GvbVyAOUFroSttS07yJuN5JG'
);

-- VEÍCULOS
INSERT INTO veiculos (
    id,
    categoria,
    modelo,
    capacidade_carga,
    ultima_checagem,
    localizacao,
    status
) VALUES
(
    100,
    'Moto Utilitária',
    'Honda CG 160 Cargo',
    '160 Kg',
    '2026-01-15',
    'Santo André - SP',
    'disponivel'
),
(
    200,
    'Caminhão Médio',
    'Volkswagen Delivery 11.180',
    '7 Toneladas',
    '2026-02-10',
    'São Bernardo do Campo - SP',
    'em_rota'
);

-- MOTORISTAS
INSERT INTO motoristas (
    cpf,
    nome,
    idade,
    veiculo_id,
    status
) VALUES
(
    '111.111.111-11',
    'João Teste',
    35,
    100,
    'Disponível'
),
(
    '222.222.222-22',
    'Maria Exemplo',
    42,
    200,
    'Em rota'
);

-- ROTAS
INSERT INTO rotas (
    origem,
    destino,
    distancia_km,
    descricao
) VALUES
(
    'Santo André - SP',
    'São Paulo - SP',
    25.50,
    'Rota de demonstração'
);

-- ENTREGAS
INSERT INTO entregas (
    id,
    nome,
    descricao,
    segredo,
    peso_kg,
    quantidade,
    destino,
    status,
    solicitante,
    dimensoes_cm_xyz,
    veiculo_id,
    motorista_cpf,
    rota_id
) VALUES
(
    'ENT001',
    'Notebook',
    'Notebook corporativo',
    0,
    2.50,
    1,
    'Rua Exemplo, 123',
    'Em rota',
    'Empresa Demo',
    '35,25,5',
    100,
    '111.111.111-11',
    1
),
(
    'ENT002',
    'Monitor',
    'Monitor 24 polegadas',
    0,
    4.00,
    1,
    'Av. Demonstração, 456',
    'Pendente',
    'Cliente Teste',
    '60,40,15',
    200,
    '222.222.222-22',
    1
);

-- OCORRÊNCIAS
INSERT INTO ocorrencias (
    entrega_id,
    descricao
) VALUES
(
    'ENT001',
    'Entrega saiu para rota normalmente.'
);