Dynamic ACF Gutenberg Block Register
===

### Quick Start
1. Include this on on your functions.php
2. Search for '/eweb/' on the gutenberg-block.php and replace it with your theme name
3. Create a folder named 'blocks' on your theme
4. Inside blocks folder create a folder for each gutenberg blocks e.g. create 'accordion-block' folder inside blocks folder
5. Inside 'accordion-block' folder create/add:
- template.php - you can add your templete code here
- preview.png - to view your block preview
- script.js - add your js code for the respective block here. You can enqueue or setup gulp to compile
- style.css/scss - add you css/scss code for the respective block here. you can enqueue or setup gulp to compile.

### Suggestion
Recommeded to use scss and setup gulp to compile scss and js from block folder.

---------------------------------
Now you're ready to go! The next step is easy to say, but harder to do: make an awesome WordPress theme. :)

Good luck!
