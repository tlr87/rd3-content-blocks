(function () {

    tinymce.PluginManager.add(
        'rd3_content_block',
        function (editor) {

            /*
             * Do not add the button when editing
             * an RD3 Content Block.
             */
            if (
                window.rd3IsContentBlock === true
            ) {
                return;
            }

            editor.addButton(
                'rd3_content_block',
                {
                    text: 'Insert RD3 Block',
                    icon: false,

                    onclick: function () {

                        var blocks =
                            window.rd3ContentBlocks || [];

                        if (blocks.length === 0) {

                            alert(
                                'No RD3 Content Blocks found.'
                            );

                            return;
                        }

                        editor.windowManager.open({

                            title:
                                'Insert RD3 Content Block',

                            body: [
                                {
                                    type: 'listbox',
                                    name: 'block',
                                    label: 'Content Block',
                                    values: blocks
                                }
                            ],

                            onsubmit: function (e) {

                                var block =
                                    e.data.block;

                                if (!block) {
                                    return;
                                }

                                editor.insertContent(
                                    '[rd3_block id="' +
                                    block +
                                    '"]'
                                );
                            }

                        });

                    }
                }
            );

        }
    );

})();