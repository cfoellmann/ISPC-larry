TODO
-   css/print.css is useless in current state
-   css/iehacks.css is useless in current state
-   css/ie7hacks.css is useless in current state

-   JS/jQuery: nach [Table Row and Column Highlighting](http://css-tricks.com/row-and-column-highlighting/)
    -   js-code is integrated in [main.tpl.inc](https://github.com/foe-services/ispc-larry/blob/master/ispc-larry/templates/main.tpl.htm)
    -   <colgroup class=""></colgroup> added to [mail_domain_admin_list.htm](https://github.com/foe-services/ispc-larry/blob/master/ispc-larry/templates/mail/mail_domain_admin_list.htm)
    -   and added to [mail_domain_list.htm](https://github.com/foe-services/ispc-larry/blob/master/ispc-larry/templates/mail/mail_domain_list.htm)
    -   -> not working :-( -> needs trouble shooting

-   Integrate: [tablesorter 2.0](http://tablesorter.com/docs/)
    -   although it will only sort the currently displayed rows it is a start.
    -   I added the library and am trying to figure out what classes and IDs are necessary to make it work

-   Frontend config pages
    -   for the enduser in tools module
    -   for the admin in admin module

-   Check all template files for missing fields