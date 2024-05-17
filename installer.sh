#! /bin/bash
echo Hello from aj !

# Setting colour code for script to make it more interactive
infoColor='\033[1;34m'          # Blue
warningColor='\033[1;33m'       # Yellow
errorColor='\033[1;31m'         # Red
resetColor='\033[0m'            # Reset color
successColor='\033[1;32m'       # Green

# Function to print message in cutom format 
printMessage(){
    message=$1
    colorCode=$2
    echo -e "${colorCode}$env_file  $message \n ${resetColor}"        
}

#Project .env file
envFile=".env"

# Function to replace key with new value and append key value if key is not available
replaceKeyValue() {

    if grep -q "^$1=" "$envFile"; then

        sed -i "s/^$1=.*/$1=$2/" "$envFile"
        printMessage "Value for $1 replaced with $2 in $envFile" $successColor


    elif grep -q "# $1" "$envFile"; then
 
        sed -i "s/# $1=.*/$1=$2/" "$envFile"
        printMessage "Value for $1 replaced with $2 in $envFile" $successColor

    else
        echo -e "\n$1=$2" >> .env
        printMessage "Added $1 key and $2 value in $envFile as it was missing in $envFile "  $successColor

    fi

}

askForUserInput()
{
    read -p "$1 " $2

    if [ -z "$2" ]; then
        printMessage  $1 " cannot be left empty." $errorColor        
        read -p "$1 " $2
    fi
}


#Checking for php and php version to run Laravel 11
if ! command -v php &> /dev/null
then
    printMessage "PHP is not installed. Please install PHP before proceeding." $errorColor
    exit 1
else
   	phpVersion=$(php -r "echo PHP_VERSION;")

    if [ "$(printf '%s\n' "8.2" "$phpVersion" | sort -V | head -n 1)" != "8.2" ]; then

        printMessage "The installed PHP version is  $phpVersion. Requirement is atleast 8.2 or greater to proceed." $errorColor
        exit 1

    else
        printMessage "PHP $phpVersion is perfect as per the requirements" $successColor
    fi
fi


#Checking for Composer and Composer version to run laravel 11
if ! command -v composer &> /dev/null
then
    printMessage "Composer is not installed. Please install Composer before proceeding." $errorColor
    exit 1
else
    composerVersion=$(composer --version | grep -oP '(?<=Composer version )(\d+\.\d+\.\d+)')
    if [ "$(printf '%s\n' "2.7.6" "$composerVersion" | sort -V | head -n 1)" != "2.7.6" ]; then

        printMessage "Composer version $composerVersion is installed and it must be greater than or equal to 2.7.6 to proceed." $errorColor
        exit 1

    else
       printMessage "Composer version is $composerVersion installed and we are good to go." $successColor 

       composer install       
    fi
fi

#Copying .env.example to .env
if [ -f .env ]; then
    printMessage ".env file already exists." $warningColor
else
    if [ -f .env.example ]; then
        cp .env.example .env
        printMessage "env file created successfully."$successColor

    else
        printMessage ".env.example file not found." $errorColor
        exit 1
    fi
fi

# Seting App Key

#  php artisan key:generate


#Properties that have a default value but user can configure its value
declare -A arrayWithDefaults
arrayWithDefaults["DB_CONNECTION"]="mysql"
arrayWithDefaults["DB_HOST"]="localhost"
arrayWithDefaults["DB_PORT"]="3306"

for key in "${!arrayWithDefaults[@]}"; do

    read -p "Do you want to use '${arrayWithDefaults[$key]} as the $key ? Press enter to confirm, or type a different host name if you prefer: " userInput

    if [ -z "$userInput" ]; then
        userInput=${arrayWithDefaults[$key]}
    fi
    replaceKeyValue $key $userInput
done



#Properties that don't have any default value
arrayWithoutDefaults=("DB_DATABASE" "DB_USERNAME" "DB_PASSWORD")

for key in "${arrayWithoutDefaults[@]}";do
   
   read -p "Please enter value for $key :" userInputValues

    if [ -z "$userInputValues" ]; then
        printMessage "The database name must be provided; it cannot be left empty." $errorColor        

        read -p "Please enter value for $key :" userInputValues
        replaceKeyValue $key $userInputValues

    fi

    if [ -n "$userInputValues" ]; then

        replaceKeyValue $key $userInputValues
    fi

done

# Crontab section 

# Function to add cron job
addCronJob() {
    cron_command=$1
    # Add the cron job to the user's crontab
    (crontab -l ; echo "$1 $cron_command") | crontab -
}


read -p "Do you want to add an entry to the crontab? (yes/no): " addCronJob

case "$addCronJob" in
    [yY]|[yY][eE][sS])

        # Prompt the user to choose the frequency
        echo "Choose the frequency:"
        echo "1. Every minute"
        echo "2. Every 10 minutes"
        echo "3. Hourly"
        
        read -p "Enter your choice (1/2/3): " choice

        # Check the user's choice
        case "$choice" in
            1)
                addCronJob "* * * * *"
                printMessage "Cron job added to run every minute." $successColor
                ;;
            2)
                addCronJob "*/10 * * * *"
                printMessage "Cron job added to run every 10 minutes." $successColor
                ;;
            3)
                addCronJob "0 * * * *"
                printMessage "Cron job added to run hourly." $successColor
                ;;
            *)
                printMessage "Invalid choice. No cron job added." $errorColor
                ;;
        esac
        ;;
    [nN]|[nN][oO])
        printMessage "No cron job added." $errorColor
        ;;
    *)
        printMessage "Invalid choice. No cron job added." $errorColor
        ;;
esac


# Adding supervisor for Jobs and Queue

if ! command -v supervisord &>/dev/null; then
    printMessage "Installing Supervisor..." $successColor
    sudo apt-get update
    sudo apt-get install -y supervisor
fi

# askForUserInput "Please enter supervisor name :" supervisorName

# askForUserInput "Please enter the number of worker you want for this queue ?" noOfWorker

# askForUserInput "Please enter comma seperated queue name :" queueName

supervisorName="laravel-11-default"
queueName="sms"
noOfWorker=1

# Creating a Supervisor configuration file for Laravel queue worker

touch $PWD/storage/logs/$supervisorName-worker.log 

cat <<EOF | sudo tee /etc/supervisor/conf.d/$supervisorName-worker.conf > /dev/null
[program:$supervisorName-worker]
process_name=%(program_name)s_%(process_num)02d
command=php $PWD/artisan queue:work --queue=$queueName --tries=3
autostart=true
autorestart=true
user=$(whoami)
numprocs=$noOfWorker
redirect_stderr=true
stdout_logfile=$PWD/storage/logs/$supervisorName-worker.log
EOF

sudo sed -i "s|$PWD/|$(pwd)/|g" /etc/supervisor/conf.d/$supervisorName-worker.conf
sudo sed -i "s|$(whoami)|$(whoami)|g" /etc/supervisor/conf.d/$supervisorName-worker.conf

# Update Supervisor to read the new configuration
sudo supervisorctl reread
sudo supervisorctl update

# Start the Laravel worker
sudo supervisorctl start $supervisorName-worker:*

# Git hooks 


# Path to the hooks directory
hooks_dir=".git/hooks"

# Name of the hook file
hook_file="post-merge"

# Content of the hook file
hook_content="#!/bin/bash\n\n# Run php artisan optimize after pulling changes\nphp $PWD/artisan optimize\n"

# Check if the hooks directory exists
if [ ! -d "$hooks_dir" ]; then
    mkdir -p "$hooks_dir"
fi

# Check if the hook file exists
if [ ! -f "$hooks_dir/$hook_file" ]; then
    # Create the hook file
    echo -e "$hook_content" > "$hooks_dir/$hook_file"
    chmod +x "$hooks_dir/$hook_file"
    echo "Created $hook_file hook."
else
    echo "$hook_file hook already exists."
fi

# Import database dump file

askForUserInput "Press 1 to import database dump and 0 to skip this step (0/1) :" dbDump

case $dbDump in 
    1)
        askForUserInput "Please write the filename stored in $PWD : " dumpFile 
        databaseName=$(grep "DB_DATABASE" .env | cut -d'=' -f2)
        userName=$(grep "DB_USERNAME" .env | cut -d'=' -f2)
        password=$(grep "DB_PASSWORD" .env | cut -d'=' -f2)

        mysql -u "$username" -p"$password" -e "CREATE DATABASE IF NOT EXISTS $databaseName;"

        mysql -u "$username" -p"$password" "$databaseName" < "$dumpFile"
        
        printMessage "Database Imported successfully!" $successColor

        ;;
    0)
            printMessage "Database Import skipped" $successColor

        ;;
    *)
        printMessage "Invalid Option selected" $errorColor
    ;;
esac

#comment added
