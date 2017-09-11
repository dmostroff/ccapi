# /home/DANO/projects/ccapi
HOMEDIR=/d/Projects/ccapi
cd $HOMEDIR
########### BEGIN 2017-08-25 10:22:14 ###############
echo '########### BEGIN 2017-08-25 10:22:14 ###############'>$HOMEDIR/scripts/adm_settings_create.sh
curl http://ccapi.com/meta/adm_settings>>$HOMEDIR/scripts/adm_settings_create.sh
$HOMEDIR/scripts/adm_settings_create.sh

#echo '########### BEGIN 2017-08-25 10:22:14 ###############'>$HOMEDIR/scripts/adm_tags_create.sh
#curl http://ccapi.com/meta/adm_tags>>$HOMEDIR/scripts/adm_tags_create.sh
#$HOMEDIR/scripts/adm_tags_create.sh

echo '########### BEGIN 2017-08-25 10:22:14 ###############'>$HOMEDIR/scripts/adm_users_create.sh
curl http://ccapi.com/meta/adm_users>>$HOMEDIR/scripts/adm_users_create.sh
$HOMEDIR/scripts/adm_users_create.sh

echo '########### BEGIN 2017-08-25 10:22:14 ###############'>$HOMEDIR/scripts/cc_action_create.sh
curl http://ccapi.com/meta/cc_action>>$HOMEDIR/scripts/cc_action_create.sh
$HOMEDIR/scripts/cc_action_create.sh

echo '########### BEGIN 2017-08-25 10:22:14 ###############'>$HOMEDIR/scripts/cc_baltransferinfo_create.sh
curl http://ccapi.com/meta/cc_baltransferinfo>>$HOMEDIR/scripts/cc_baltransferinfo_create.sh
$HOMEDIR/scripts/cc_baltransferinfo_create.sh

echo '########### BEGIN 2017-08-25 10:22:14 ###############'>$HOMEDIR/scripts/cc_cards_create.sh
curl http://ccapi.com/meta/cc_cards>>$HOMEDIR/scripts/cc_cards_create.sh
$HOMEDIR/scripts/cc_cards_create.sh

echo '########### BEGIN 2017-08-25 10:22:14 ###############'>$HOMEDIR/scripts/cc_company_create.sh
curl http://ccapi.com/meta/cc_company>>$HOMEDIR/scripts/cc_company_create.sh
$HOMEDIR/scripts/cc_company_create.sh

echo '########### BEGIN 2017-08-25 10:22:14 ###############'>$HOMEDIR/scripts/cc_transaction_create.sh
curl http://ccapi.com/meta/cc_transaction>>$HOMEDIR/scripts/cc_transaction_create.sh
$HOMEDIR/scripts/cc_transaction_create.sh

echo '########### BEGIN 2017-08-25 10:22:14 ###############'>$HOMEDIR/scripts/client_address_create.sh
curl http://ccapi.com/meta/client_address>>$HOMEDIR/scripts/client_address_create.sh
$HOMEDIR/scripts/client_address_create.sh

echo '########### BEGIN 2017-08-25 10:22:14 ###############'>$HOMEDIR/scripts/client_business_create.sh
curl http://ccapi.com/meta/client_business>>$HOMEDIR/scripts/client_business_create.sh
$HOMEDIR/scripts/client_business_create.sh

echo '########### BEGIN 2017-08-25 10:22:14 ###############'>$HOMEDIR/scripts/client_cc_create.sh
curl http://ccapi.com/meta/client_cc>>$HOMEDIR/scripts/client_cc_create.sh
$HOMEDIR/scripts/client_cc_create.sh

echo '########### BEGIN 2017-08-25 10:22:14 ###############'>$HOMEDIR/scripts/client_cchistory_create.sh
curl http://ccapi.com/meta/client_cchistory>>$HOMEDIR/scripts/client_cchistory_create.sh
$HOMEDIR/scripts/client_cchistory_create.sh

echo '########### BEGIN 2017-08-25 10:22:14 ###############'>$HOMEDIR/scripts/client_financials_create.sh
curl http://ccapi.com/meta/client_financials>>$HOMEDIR/scripts/client_financials_create.sh
$HOMEDIR/scripts/client_financials_create.sh

echo '########### BEGIN 2017-08-25 10:22:14 ###############'>$HOMEDIR/scripts/client_person_create.sh
curl http://ccapi.com/meta/client_person>>$HOMEDIR/scripts/client_person_create.sh
$HOMEDIR/scripts/client_person_create.sh

