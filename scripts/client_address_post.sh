curl -H "Content-Type: application/json" -X POST -d '{"client_id":"1","address_type":"home","address_1":"120 Mt Vernon Way","address_2":"","city":"Mt Vernon","state":"VA","country":"USA","valid_from":"1732-01-01","valid_to":"1799-01-01","recorded_on":"2017-08-25 14:34:26"}' http://ccapi.com/client/address
curl -H "Content-Type: application/json" -X POST -d '{"client_id":"1","address_type":"previous","address_1":"120 Elm Street","address_2":"","city":"Arlington","state":"VA","country":"USA","valid_from":"1732-01-01","valid_to":"1744-01-01","recorded_on":"2017-08-25 15:39:08"}' http://ccapi.com/client/address
curl -H "Content-Type: application/json" -X POST -d '{"client_id":"2","address_type":"home","address_1":"501 Independence Ave","address_2":"","city":"Monticello","state":"VA","country":"USA","valid_from":"1770-01-01","valid_to":"1820-07-04","recorded_on":"2017-08-25 15:41:55"}' http://ccapi.com/client/address
curl -H "Content-Type: application/json" -X POST -d '{"client_id":"2","address_type":"previous","address_1":"501 Independence Ave","address_2":"","city":"Shadwell","state":"VA","country":"USA","valid_from":"1743-04-02","valid_to":"1770-11-23","recorded_on":"2017-08-25 15:42:54"}' http://ccapi.com/client/address
