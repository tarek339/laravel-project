## app/Console/Commands/CheckDriversCommand.php

**The command will loop threw the drivers and checks wich license, driver card and qualification number is about to expire in two and one month**

**Step 1**

- Create variable of todays date
- Create a variable of date in two month
- Create a variable of date in one month

1. Check drivers license expiration
    - Loop threw drivers and store the drivers in an array of license_expirations
2. Check driver card expiration
    - Loop threw drivers and store the drivers in an array of driver_card_expirations
3. Check qualification expiration
    - Loop threw drivers and store the drivers in an array of qualification_expiration
