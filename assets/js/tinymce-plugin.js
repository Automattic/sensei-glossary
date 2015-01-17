/**
 * Tiny MCE button functionality for
 * the Sensei Glossary extension
 */
jQuery( document ).ready( function ( e ) {

    tinymce.PluginManager.add( 'sensei_glossary', function( editor, url) {

        /**
         * popupSubmit
         *
         * When the ok button is press on the popup dialog
         * @since 1.0.0
         */
        var popupSubmit = function ( e ) {
            // Insert content when the window form is submitted
            var itemID = jQuery( 'input#glossary-id').val();
            if (! itemID) {
                alert( 'This glossary item requires an ID.' );
                return false;
            }

            var itemTitle = jQuery( 'input#glossary-title').val();

            if (! itemTitle) {
                itemTitle = 'more info';
            }

            var shortCode = '[sensei_glossary id="'+ itemID +'" ]'+ itemTitle +' [/sensei_glossary]';
            editor.insertContent( shortCode );

            return;
        };

        /**
         * var bodyElements
         *
         * used to configure the modal popup form fields
         * @since 1.0.0
         */
        var bodyElements = [{

                name: 'search',
                type: 'textbox',
                id: 'glossary-id',
                size: 20,
                autofocus: true,
                label: 'Glossary ID:',
            },

            {
                name: 'title',
                type: 'textbox',
                id: 'glossary-title',
                size: 40,
                autofocus: true,
                label: 'Title:',
            },

            {
                type: 'container',
                html: '<p>To find the ID, go to edit the Glossary<br />item. On the edit screen look ' +
                'up at the URl. You will see the numeric <br /> ID next to "?post=" . Take only this number and use it here.</p>'
            }
        ];

        /**
         * onButtonClick
         *
         * Respond to the MCE icon button click
         * @since 1.0.0
         */
        var onButtonClick = function ( e ) {

            // Open window
            editor.windowManager.open({
                title: 'Insert Glossary Item',
                body: bodyElements,
                onsubmit: popupSubmit,
            });


        }// en onButtonClick


        /**
         * MAIN()
         *
         * Add the button to the mce editor. This configuration also bring together all the predefined functions.
         *
         * @since 1.0.0
         */
        editor.addButton('sensei_glossary', {
            text: 'Glossary',
            icon: 'sensei_glossary',
            tooltip: 'Insert Glossary Item',
            onclick: onButtonClick
        });
    });// end tinymce.PluginManager.Add

});// end jQuery