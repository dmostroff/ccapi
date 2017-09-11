OUTFILE=scripts/client_address_post.sh
echo>$OUTFILE
URL="http://ccapi.com/client/address"
CURL="curl -H \"Content-Type: application/json\" -X POST -d '\\1' $URL"
SED="'s!^(.+)$!$CURL!g'"
cat db/data/client_address.json | sed -E "s/^(.+)$/curl -H \"Content-Type: application\/json\" -X POST -d '\1' http:\/\/ccapi.com\/client\/address/g">$OUTFILE
#echo $SED