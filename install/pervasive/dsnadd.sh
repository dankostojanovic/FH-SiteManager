#!/bin/sh
#
# dsnadd -- add an entry to ODBC ini file
#

#Usage function declared here

usage(){
cat <<EOF

dsnadd - utility for creating server-side or client-side Pervasive DSNs.
Usage:
To list existing DSNs:
    dsnadd -l
To add an engine DSN that connects to a local named database:
    dsnadd -dsn= -db= -uid= -pwd=
     [--openmode=] [--translate=auto]
To add a client DSN that connects to a remote named database:
    dsnadd -dsn= -host= -db=
     -uid= -pwd=
     [--openmode=][--translate=auto] [-port=]

Options (default value is given in square brackets after description):
  --dsn-name or -dsn    Data Source Name
  --database or -db     database name to use (local or remote)
  --srv-host or -host   server host name (client DSN)
  --srv-port or -port   server port number (client DSN) [1583]
  --openmode or -omode  Mode for opening the database [Normal]
  --translate           value of auto translates to server encoding

Legacy Options
  --srv-dsn  or -sdsn   server DSN (client DSN) []
  -engdsn               create deprecated, legacy-style Engine DSN
  -clntdsn              create deprecated, legacy-style Client DSN

Other Options (typically used only for application development and testing)
  --odbc-ini or -ini    ODBC ini file name [/usr/local/psql/etc/odbc.ini]
  --dsn-desc or -desc   DSN description [[: ]/]
  --drv-path or -drv    driver location directory [/usr/local/psql/lib]
  --drv-desc            driver description [Pervasive ODBC Interface]

To add a deprecated, legacy-style engine DSN:
    dsnadd -dsn= -db= -uid= -pwd= -engdsn
     [--openmode=] [--translate=auto]

To add a deprecated, legacy-style client DSN:
    dsnadd -dsn= -host= -sdsn= -clntsdn
     -uid= -pwd=
     [--openmode=] [--translate=auto]

EOF
}


#Setting up system-dependent variables

os=`uname -s`
if test SunOS = "$os"; then
	awk=nawk
	grep=/usr/xpg4/bin/grep
	if [ "X$PVSW_ROOT" = "X" ] ; then
		PVSW_ROOT=/opt/PVSWpsql
	fi
else
	awk=awk
	grep=grep
	if [ "X$PVSW_ROOT" = "X" ] ; then
		PVSW_ROOT=/usr/local/psql
	fi
fi

#Setting up common variables

my_rem=";disabled `date '+%m/%d/%y-%H:%M:%S'` "
dsn_name=""
dsn_desc=""
srv_dsn=""
srv_host=""
user_id=""
user_pwd=""
print_dsns=""
drv_path="$PVSW_ROOT/lib64"
odbc_ini="$PVSW_ROOT/etc/odbc.ini"
open_mode=""
pvtranslate=""

#Parsing command line

for opt
do
	if test -n "$opt_prev"; then
		eval "$opt_prev=\$opt"
		opt_prev=
		continue
	fi
	case "$opt" in
	-*=*) optarg=`echo "$opt" | sed 's/[-_a-zA-Z0-9]*=//'` ;;
	*) optarg= ;;
	esac
	case "$opt" in
	-h | -help | --help | --h)
		usage ; exit 0 ;;
	-l | -list | --list | --l)
		print_dsns="yes" ;;
	-ini | --ini | -odbc-ini | --odbc-ini)
		opt_prev=odbc_ini ;;
	-ini=* | --ini=* | -odbc-ini=* | --odbc-ini=*)
		odbc_ini="$optarg" ;;
	-dsn | --dsn | -dsn-name | --dsn-name)
		opt_prev=dsn_name ;;
	-dsn=* | --dsn=* | -dsn-name=* | --dsn-name=*)
		dsn_name="$optarg" ;;
	-desc | --desc | -dsn-desc | --dsn-desc)
		opt_prev=dsn_desc ;;
	-desc=* | --desc=* | -dsn-desc=* | --dsn-desc=*)
		dsn_desc="$optarg" ;;
	-drv | --drv | -drv-path | --drv-path)
		opt_prev=drv_path ;;
	-drv=* | --drv=* | -drv-path=* | --drv-path=*)
		drv_path="$optarg" ;;
	-drv-desc | --drv-desc)
		opt_prev=drv ;;
	-drv-desc=* | --drv-desc=*)
		drv_desc="$optarg" ;;
	-open-mode | --open-mode | -omode | --omode)
		opt_prev=open_mode ;;
	-open-mode=* | --open-mode=* | -omode=* | --omode=*)
		open_mode="$optarg" ;;
	--translate | -translate)
		opt_prev=pvtranslate ;;
	--translate=* | -translate=*)
		pvtranslate="$optarg" ;;
	-sdsn | --srv-dsn)
		opt_prev=srv_dsn ;;
	-sdsn=* | --srv-dsn=*)
		srv_dsn="$optarg" ;;
	-uid | --user-id)
		opt_prev=user_id ;;
	-uid=* | --user-id=*)
		user_id="$optarg" ;;
	-pwd | --user-pwd)
		opt_prev=user_pwd ;;
	-pwd=* | --user-pwd=*)
		user_pwd="$optarg" ;;
	-host | --host | -srv-host | --srv-host)
		opt_prev=srv_host ;;
	-host=* | --host=* | -srv-host=* | --srv-host=*)
		srv_host="$optarg" ;;
	-port | --port | -srv-port | --srv-port)
		opt_prev=srv_port ;;
	-port=* | --port=* | -srv-port=* | --srv-port=*)
		srv_port="$optarg" ;;
	-drv_name | --drv_name)
		opt_prev=drv_name ;;
	-drv_name=* | --drv_name=*)
		drv_name="$optarg" ;;
	-database | --database | -db | --db)
		opt_prev=dbq ;;
	-database=* | --database=* | -db=* | --db=*)
		dbq="$optarg" ;;
        -engdsn)
                engdsn=yes
                drv_desc="Pervasive ODBC Engine Interface";;
        -clntdsn)
                clntdsn=yes;
                drv_desc="Pervasive ODBC Client Interface";;

	-*)
		{ echo "Error: $opt: invalid option" 1>&2; usage; exit 1; } ;;
	*)
		{ echo "Error: $opt: unexpected argument" 1>&2; usage; exit 1; } ;;
	esac
done

#Handling command line errors

if test -n "$opt_prev"; then
	{ echo "Error: Missing argument to --`echo $opt_prev|sed 's/_/-/g'`" 1>&2; usage; exit 1; }
fi

if test -z "$dsn_name"; then
	if test -z "$print_dsns"; then
		if test -n "$dbq"; then
			echo "Warning: DSN is not specified, assuming \"$dbq\""
			dsn_name=$dbq
		elif test -n "$srv_dsn"; then
			echo "Warning: DSN is not specified, assuming \"$srv_dsn\""
			dsn_name=$srv_dsn
		else
			{ echo "Error: Data source name not specified" 1>&2; usage; exit 1; }
		fi
	fi
fi

if test -z "$srv_host"; then
	if test -z "$print_dsns"; then
		if test -z "$dbq"; then
			{ echo "Error: Both server host name and database name not specified."
			 echo "You need to specify host name or database name to create client-side or server-side DSN." 1>&2; 
			 usage; exit 1; }
		fi
	fi
fi


# if test -n "$dbq" ; then
#         if test -z "$print_dsns"; then
#                 if  test -n "$srv_host" ; then
#                         { echo "Error: Both server host name and database name specified. Wrong usage - specify either database name for server-side DSN or server host name for client side DSN" 1>&2; usage; exit 1; }
#                 fi
#         fi
# fi

#Determining which DSN will be created

if test -n "$srv_host" ; then
		local=no
else
        local=yes
fi

if test -n "$open_mode" ; then
	case "$open_mode" in
	0 | 1 | -1 | -4)
		;;
	Normal | normal)
		open_mode=0 ;;
	Read-only | read-only)
		open_mode=1 ;;
	Accelerated | accelerated)
		open_mode=-1 ;;
	Exclusive | exclusive)
		open_mode=-4 ;;
	*)
		{ echo "Error: bad open mode \"$open_mode\", should be Normal, Read-only, Accelerated, or Exclusive" 1>&2; usage; exit 1; } ;;
	esac
fi

if test -n "$pvtranslate" ; then
	case "$pvtranslate" in
	auto)
		;;
	*)
		{ echo "Error: bad translate value \"$pvtranslate\", should be \"auto\" or unspecified" 1>&2; usage; exit 1; } ;;
	esac
fi

# Check for conflicting options
#
if [ "X$local" = "Xyes" ] ; then
    if [ "X$clntdsn" = "Xyes" ] ; then
        { echo "Error: -clntdsn can only be specified with a client-side DSN." 1>&2; usage; exit 1; }
    fi
else
    if [ "X$engdsn" = "Xyes" ] ; then
        { echo "Error: -engdsn can only be specified with a server-side DSN." 1>&2; usage; exit 1; }
    fi
fi

if [ "X$engdsn" = "Xyes" ] ; then
    if [ "X$clntdsn" = "Xyes" ] ; then
        { echo "Error: Both -engsdn and -clntdsn specified." 1>&2; usage; exit 1; }
    fi
fi

if test -n "$srv_dsn"; then
    if test -n "$dbq"; then 
        { echo "Error: Both Server DSN and database name specified." 1>&2; usage; exit 1; }
    fi
fi


# Setting up defaults here
#

if test -z "$srv_dsn"; then
	if test -z "$print_dsns"; then
		if [ "X$local" = "Xno" ] ; then
            if test -z "$dbq"; then
                echo "Warning: Server DSN or database name is not specified, using \"$dsn_name\""
		    	srv_dsn="$dsn_name"
			fi
		fi
	fi
fi

# Undocumented feature - you can pass driver name using -drv_name= ;)
if test -z "$drv_name" ; then
	if [ "X$local" = "Xyes" ] ; then
        if [ $(uname -s) = "Darwin" ]
        then
            drv_name="libodbcci.dylib"
        else
            drv_name="libodbcci.so"
        fi
	else
        if [ $(uname -s) = "Darwin" ]
        then
            drv_name="odbcci.dylib"
        else
            drv_name="odbcci.so"
        fi
	fi
fi

# If drv_desc specified, then also allow old style driver name as value
if test -z "$drv_desc"; then
#   if [ "X$local" = "Xyes" ] ; then
#                drv_desc="Pervasive ODBC Engine Interface"
#       else
#                drv_desc="Pervasive ODBC Client Interface"
#       fi
    drv_desc="Pervasive ODBC Interface"
    drv_val="Pervasive ODBC Interface"
fi

if test -z "$srv_port"; then
	srv_port="1583"
fi

if test -z "$dsn_desc"; then
	if [ "X$local" = "Xyes" ] ; then
		dsn_desc="$drv_desc: database $dbq"
	else
        if test -z "$srv_dsn"; then
            dsn_desc="$drv_desc: $srv_host:$srv_port/$dbq"
        else
			dsn_desc="$drv_desc: $srv_host:$srv_port/$srv_dsn"
		fi
	fi
fi

if test -n "$drv_path"; then
	for path in `echo "$drv_path"|sed 's/:/ /g'`; do
		if test -f "$path"/"$drv_name"; then
			drv_file="$path"/"$drv_name"
			break
		fi
	done
else
	# just in case it could be found in LD_LIBRARY_PATH
	drv_file="$drv_name"
fi

# finish user-specified driver file here
if test -z "$drv_val"; then
    drv_val="$drv_file"
fi

if test -z "$drv_file"; then
	{ echo "Error: No driver found at specified locations (drv_name=$drv_name, drv_path=$drv_path)" 1>&2; usage; exit 1; }
fi

if test -z "$open_mode"; then
        open_mode="0"
fi

#Doing actual parsing here
echo

# make sure the file exists _and_ is not empty. jme - 28992
if test -s "$odbc_ini"; then
	odbc_ini_new="$odbc_ini".new
	odbc_ini_bak="$odbc_ini".bak
	dsn_enum_section=
	if $grep -i -q \
			"^[ 	]*\[[ 	]*odbc data sources[ 	]*\]" \
			"$odbc_ini"; then
		dsn_enum_section=yes
	fi
	dsn_name_section=
	if $grep -i -q \
			"^[ 	]*\[[ 	]*$dsn_name[ 	]*\]" \
			"$odbc_ini"; then
		dsn_name_section=yes
	fi

	if test -z "$print_dsns"; then

		if [ "X$local" = "Xyes" ] ; then
			volatile_line="\"DBQ=$dbq\nUID=$user_id\nPWD=$user_pwd\nOpenMode=$open_mode\nPvTranslate=$pvtranslate\n\""
		else
            if test -z "$dbq"; then                        
				volatile_line="\"ServerDSN=$srv_dsn\nServerName=$srv_host:$srv_port\nUID=$user_id\nPWD=$user_pwd\nOpenMode=$open_mode\nPvTranslate=$pvtranslate\n\""
            else
                volatile_line="\"DBQ=$dbq\nServerName=$srv_host:$srv_port\nUID=$user_id\nPWD=$user_pwd\nOpenMode=$open_mode\nPvTranslate=$pvtranslate\n\""
            fi
		fi

		cat "$odbc_ini" |
			$awk " \
				BEGIN { \
                    ods_found = 0; \
					counting_dsns = 0; \
					dsn_counted = 0; \
					dsn_added = 0; \
					old_dsn = 0; \
				} \
				/^[ \t]*\[[^\]]*\]/ { \
					split(\$0, fld, \"[ \t]*\\\\[[ \t]*|[ \t]*\\\\][ \t]*\"); \
					if (\"\" == fld[1]) i = 2; else i = 1;
					section = tolower(fld[i]); \
					if (section == \"odbc data sources\") { \
						counting_dsns = 1; \
                        ods_found = 1; \
					} \
					else { \
						counting_dsns = 0; \
						if (section == tolower(\"$dsn_name\")) \
							old_dsn = 1; else old_dsn = 0; \
						if (0 == dsn_added && \
								(0 != dsn_counted || \
								\"\" == \"$dsn_enum_section\") \
							) { \
							dsn_added = 1; \
							if (0 == old_dsn) { \
								print \"[$dsn_name]\"; \
							} \
							else { \
								print \$0; \
							} \
                            print \"Driver=$drv_val\"; \
							print \"Description=$dsn_desc\"; \
							printf $volatile_line ; \
							if (0 == old_dsn) { \
								print \"\"; \
							} \
							else { \
								next; \
							} \
						} \
					} \
				} \
				0 != counting_dsns && /^[ \t]*[_a-zA-Z0-9]+[ \t]*=/ { \
					if (0 == dsn_counted) { \
						dsn_counted = 1; \
						print \"$dsn_name=$drv_desc\"; \
					} \
					split(\$0, fld, \"[ \t]*|[ \t]*=[ \t]*\"); \
					if (\"\" == fld[1]) i = 2; else i = 1;
					if (tolower(\"$dsn_name\") == tolower(fld[i])) { \
						print \"$my_rem\" \$0; \
						next; \
					} \
				} \
				0 != old_dsn && /^[ \t]*[^;!]+/ { \
					print \"$my_rem\" \$0; \
					next; \
				} \
				{ print; } \
				END { \
						if (ods_found == 0) { \
								print \"[ODBC Data Sources]\"; \
						} \
						if (dsn_added == 0) { \
								print \"$dsn_name=$drv_desc\"; \
								print \"\"; \
								print \"[$dsn_name]\"; \
								print \"Driver=$drv_val\"; \
								print \"Description=$dsn_desc\"; \
								printf $volatile_line ; \
						} \
				} \
				" >"$odbc_ini_new"

		if cp "$odbc_ini" "$odbc_ini_bak"; then
			# jme 28270  - changed the mv to a cp to preserve ownership
			echo "The original file $odbc_ini is saved as $odbc_ini_bak"
		else
			{ echo "Failed to rename $odbc_ini to $odbc_ini_bak" 1>&2; exit 1; }
		fi
		if cp "$odbc_ini_new" "$odbc_ini"; then
			# jme 28270  - changed the mv to a cp to preserve ownership
			rm -f $odbc_ini_new

			echo "The file $odbc_ini updated"
		else
			{ echo "Failed to rename $odbc_ini_new to $odbc_ini" 1>&2; exit 1; }
		fi
	else
		# need to print dsns
		echo "Listing DSNs:"
		echo "-----------------------------------------------"
		cat "$odbc_ini" |
			$awk " \
				BEGIN { \
				} \
				/^[ \t]*\[[^\]]*\]/ { \
					split(\$0, fld, \"[ \t]*\\\\[[ \t]*|[ \t]*\\\\][ \t]*\"); \
					if (\"\" == fld[1]) i = 2; else i = 1;
					section = tolower(fld[i]); \
					if (section == \"odbc data sources\") { \
						next; \
					} \
					else { \
						print section; \
						next; \
					} \
				} \
				{ next; }"

	fi
else
	if test -z "$print_dsns"; then
		if [ "X$local" = "Xno" ] ; then
            if test -n "$srv_dsn"; then
                dsn_or_dbq=ServerDSN"=$srv_dsn"
            else
                dsn_or_dbq=DBQ"=$dbq"
            fi
			if cat >"$odbc_ini" <&2; exit 1; }
			fi
		else
			if cat >"$odbc_ini" <&2; exit 1; }
			fi
		fi
	else
		echo "no dsns exist"
	fi
fi
