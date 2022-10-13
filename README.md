# Acquirer Gateway

Sistema responsável por abstrair o processo de comunicação com diferentes adquirentes e garantir a resiliência dos pagamentos com cartão.
Para mais informações consulte o swagger do projeto através da rota `/docs`.

## Tecnologias Utilizadas
- PHP + Swoole
- Hyperf
- PostgreSQL
- Redis

## Sistemas Relacionados
- [Acquiring Rules](https://github.com/lag-tcc-pucminas/acquiring-rules)

## Adquirentes

O projeto possui adquirentes internas que simulam a integração com uma interface de pagamentos externa.
Cada uma das adquirentes possui 50% de chance de aprovar uma transação, além de um cenário específico que simula um erro de integração ou indisponibilidade.

Adquirentes Internas:
- **Green**: retorna erro quando o valor do pagamento é composto por somente **um dígito distinto**, exemplo: 888;
- **Blue**: retorna erro quando o cartão informado não tenha sido emitido pela bandeira **visa** ou **mastercard**;
- **Red**: retorna erro quando o pagamento solicitado possuir mais do que 6 parcelas;


## Links Úteis
- [Vídeo 1 - Apresentação](https://youtu.be/YX3vLXgjSTU)
- [Postman Collection](https://www.getpostman.com/collections/5ac337cae4677043486d)


