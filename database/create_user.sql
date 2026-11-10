-- Execute com uma conta administrativa, apenas na configuração inicial.
CREATE USER 'meu_mvc_app'@'localhost'
  IDENTIFIED BY 'substitua-por-uma-senha-local';
GRANT SELECT, INSERT, UPDATE, DELETE ON meu_mvc.*
  TO 'meu_mvc_app'@'localhost';
