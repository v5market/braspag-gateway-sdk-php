# Tabela de erros

Código e descrição dos erros retornados pelas exceções *LengthException* e *InvalidArgumentException*.

| Código | Descrição | Observação |
| ------ | --------- | ---------- |
| 1000 | O nome da rua é obrigatório | - |
| 1001 | O número da residência é obrigatório | - |
| 1002 | O CEP deve possuir 8 caracteres numéricos | - |
| 1003 | O nome da cidade é obrigatório | - |
| 1004 | A sigla o Estado deve possuir 2 caracteres | - |
| 1005 | O código do país deve possuir 3 caracteres | - |
| 1500 | O e-mail informado é inválido | - |
| 2000 | O CPF informado é inválido | - |
| 2001 | O CNPJ informado é inválido | - |
| 3001 | O código do país deve seguir o padrão ISO 3166-1 alpha-3 | - |
| 4000 | E-mail do navegador é inválido | - |
| 4001 | O endereço de IP é inválido | - |
| 4502 | O e-mail do passageiro é inválido. | - |
| 4502 | O código do aeroporto de embarque é inválido. Utilize 3 caracteres. | - |
| 4503 | O código do aeroporto de destino é inválido. Utilize 3 caracteres. | - |
| 5000 | O tipo de passagem é inválido | Valores possíveis: *OneWayTrip* (Somente ida) ou *RoundTrip* (ida e volta) |
| 5500 | A data de expiração deve estar no formato MM/YYYY format | - |
| 6000 | A data de expiração não pode ser menor que a data atual | - |
| 2001 | O CNPJ do cedente é inválido | - |
| 6500 | Valor inválido | Valores possíveis: *First* ou *Used* |
| 6501 | A opção de uso do cartão de crédito é inválida | Valores possíveis:<br>*Recurring* (Compra recorrente programada),<br>*Unscheduled* (Compra recorrente sem agendamento),<br>*Installments* (Parcelamento através da recorrência) |