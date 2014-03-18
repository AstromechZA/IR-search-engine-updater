# Database schema

mysql> CREATE TABLE ir_update_state
(
    state VARCHAR(30),
    ts TIMESTAMP,
    progress INT(5)
);