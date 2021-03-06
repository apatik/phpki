<?php
#
# This is to support the NetscapeRevocationURL extension that can
# be used to check the validity of certificates issued by this CA.
# The URL to this script is embeded in all certificates issued by
# this CA.
#
# PROTOCOL:
# The client should issue an HTTP GET request using a URL that is
# the concatenation of the revocation url and certificate serial
# number. (i.e. http://www.host.dom/phpki/ns_revoke_query.php?10A5F2)
#
# The server should return a document of type
# application/x-netscape-revocation containing a single character
# '1' if the certificate is revoked, '0' if it is valid.
#
require_once('./include/std_include.php');

$serial = escapeshellcmd(trim($_SERVER['QUERY_STRING']));
#header("Content-type: application/x-netscape-revocation");
# old Reg Ex doesnt work, new should do the work
#$regexp = "^R\t.*\t.*\t$serial\t.*\t.*$";
$regexp = "^R.*$serial.*$";
if (exec("egrep '$regexp' $config[index]"))
    print '1';
else
    print '0';