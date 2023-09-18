@extends('layouts.admin.layouts-app')
@section('title', 'Edit Layout')
@php
    $css = ['https://fonts.googleapis.com/css?family=Varela+Round', 'https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i'];
    $js = ['https://code.jquery.com/jquery-3.3.1.min.js','https://use.fontawesome.com/releases/v6.1.0/js/all.js','https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js'];
    if ($layout != '') {
        $css_path = getTemplateUrl($layout) . '/assets/css/';
        if ($css_handle = opendir(getTemplateUri($layout) . 'assets/css/')) {
            while (false !== ($entry = readdir($css_handle))) {
                if ($entry != '.' && $entry != '..') {
                    array_push($css,$css_path.$entry);
                }
            }
            closedir($css_handle);
        }
        $js_path = getTemplateUrl($layout) . '/assets/js/';
        if ($js_handle = opendir(getTemplateUri($layout) . '/assets/js/')) {
            while (false !== ($entry = readdir($js_handle))) {
                if ($entry != '.' && $entry != '..') {
                    array_push($js,$js_path.$entry);
                }
            }
            closedir($js_handle);
        }
    }
@endphp
@section('content')
    <div id="gjs"></div>
@endsection
@section('scripts')
    <script>
        var css = ['{!! implode("','",$css) !!}'];
        var js = ['{!! implode("','",$js) !!}'];

        const editor = grapesjs.init({
            container: '#gjs',
            fromElement: true,
            showOffsets: true,
            assetManager: {
                upload: "{{ route('page.upload') }}",
                uploadName: 'images',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                // embedAsBase64: true,
                // assets: images
            },
            plugins: [
                'gjs-blocks-basic',
                'grapesjs-plugin-forms',
                'grapesjs-component-countdown',
                'grapesjs-plugin-export',
                'grapesjs-tabs',
                'grapesjs-custom-code',
                'grapesjs-touch',
                'grapesjs-parser-postcss',
                'grapesjs-tooltip',
                'grapesjs-tui-image-editor',
                'grapesjs-typed',
                'grapesjs-style-bg',
                'grapesjs-preset-webpage',
                'grapesjs-navbar',
            ],
            pluginsOpts: {
                'gjs-blocks-basic': {
                    flexGrid: true
                },
                'grapesjs-tui-image-editor': {
                    script: [
                        'https://uicdn.toast.com/tui.code-snippet/v1.5.2/tui-code-snippet.min.js',
                        'https://uicdn.toast.com/tui-color-picker/v2.2.7/tui-color-picker.min.js',
                        'https://uicdn.toast.com/tui-image-editor/v3.15.2/tui-image-editor.min.js',
                    ],
                    style: [
                        'https://uicdn.toast.com/tui-color-picker/v2.2.7/tui-color-picker.min.css',
                        'https://uicdn.toast.com/tui-image-editor/v3.15.2/tui-image-editor.min.css',
                    ],
                },
                'grapesjs-tabs': {
                    tabsBlock: {
                        category: 'Extra'
                    }
                },
                'grapesjs-typed': {
                    block: {
                        category: 'Extra',
                        content: {
                            type: 'typed',
                            'type-speed': 40,
                            strings: [
                                'Text row one',
                                'Text row two',
                                'Text row three',
                            ],
                        }
                    }
                },
                'grapesjs-preset-webpage': {
                    modalImportTitle: 'Import Template',
                    modalImportLabel: '<div style="margin-bottom: 10px; font-size: 13px;">Paste here your HTML/CSS and click Import</div>',
                    modalImportContent: function(editor) {
                        return editor.getHtml() + '<style>' + editor.getCss() + '</style>'
                    },
                },
                'grapesjs-navbar': {/* ...options */},
                'grapesjs-blocks-bootstrap4': {
                    blocks: {
                        'h1-block': {
                            label: 'Heading',
                            content: '<h1>Put your title here</h1>',
                            category: 'Basic',
                            attributes: {
                                title: 'Insert h1 block'
                            }
                        }
                    },
                    blockCategories: {},
                    labels: {},
                    gridDevicesPanel: true,
                    optionsStringSeparator: '::'
                }
            },
            storageManager: {
                autosave: false,
                setStepsBeforeSave: 1,
                type: 'remote',
                contentTypeJson: true,
            },
            canvas: {
                styles: css,
                scripts: js,
            }
        });
        editor.I18n.addMessages({
            en: {
                styleManager: {
                    properties: {
                        'background-repeat': 'Repeat',
                        'background-position': 'Position',
                        'background-attachment': 'Attachment',
                        'background-size': 'Size',
                    }
                },
            }
        });
        var pn = editor.Panels;
        var modal = editor.Modal;
        var cmdm = editor.Commands;
        // Update canvas-clear command
        cmdm.add('canvas-clear', function() {
            if (confirm('Are you sure to clean the canvas?')) {
                editor.runCommand('core:canvas-clear')
                setTimeout(function() {
                    localStorage.clear()
                }, 0)
            }
        });
        toastr.options = {
            closeButton: true,
            preventDuplicates: true,
            showDuration: 250,
            hideDuration: 150
        };
        // Add and beautify tooltips
        [
            ['sw-visibility', 'Show Borders'],
            ['preview', 'Preview'],
            ['fullscreen', 'Fullscreen'],
            ['export-template', 'Export'],
            ['undo', 'Undo'],
            ['redo', 'Redo'],
            ['gjs-open-import-webpage', 'Import'],
            ['canvas-clear', 'Clear canvas']
        ]
        .forEach(function(item) {
            pn.getButton('options', item[0]).set('attributes', {
                title: item[1],
                'data-tooltip-pos': 'bottom'
            });
        });
        [
            ['open-sm', 'Style Manager'],
            ['open-layers', 'Layers'],
            ['open-blocks', 'Blocks']
        ]
        .forEach(function(item) {
            pn.getButton('views', item[0]).set('attributes', {
                title: item[1],
                'data-tooltip-pos': 'bottom'
            });
        });
        var titles = document.querySelectorAll('*[title]');
        for (var i = 0; i < titles.length; i++) {
            var el = titles[i];
            var title = el.getAttribute('title');
            title = title ? title.trim() : '';
            if (!title)
                break;
            el.setAttribute('data-tooltip', title);
            el.setAttribute('title', '');
        }
        // Do stuff on load
        pn.getButton('options', 'sw-visibility').set('active', 1);
        // Save Data And View Data 
        pn.addButton('options', {
            id: 'save-data',
            className: 'fa fa-save',
            command: 'save-db',
            attributes: {
                'title': 'Save Data',
                'data-tooltip-pos': 'bottom',
            },
        });
        pn.addButton('options', {
            id: 'view-data',
            className: 'fa fa-link',
            command: 'view-page',
            attributes: {
                'title': 'View Page',
                'data-tooltip-pos': 'bottom',
            },
        });
        editor.Commands.add('view-page', {
            run: function(editor, sender) {
                sender && sender.set('active', 0); // turn off the button
                window.open("{{($page->status == 0)?url('/preview/'.$id.'/'.$connect_same) : route($page_link) }}", "_blank");
            }
        });
        editor.Commands.add('save-db', {
            run: function(editor, sender) {
                sender && sender.set('active', 0); // turn off the button
                var body = editor.getHtml();
                var css = editor.getCss();
                var js = editor.getJs();
                // editor.store();
                var data = {
                    "body": body,
                    "css": css,
                    "js": js
                };
                $.ajax({
                    type: "POST",
                    url: "{{ route('page.save_grapejs', [$id, $connect_same]) }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        data: data,
                    },
                    success: function() {
                        window.toastr.success("Page Design Save Successfully!");
                    }
                });
            }
        });
        var assetManager = editor.AssetManager;
        editor.on('asset:upload:response', (response) => {
            assetManager.add({
                type: 'image',
                src: response.src,
                height: response.height,
                width: response.width,
            });
        });

        var assets = editor.AssetManager.getAll() // <- Backbone collection
        assets.on('remove', function(asset) {
            // alert(asset.getFilename());
            $.ajax({
                url: "{{ route('page.remove-image') }}",
                type: "POST",
                headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                data: {"name":asset.getFilename()},
                dataType: "JSON",
                success: function(res) {
                }
            })
        })

        $(document).ready(function() {
            $.ajax({
                url: "{{ route('page.fetch-images') }}",
                type: "GET",
                data: {},
                dataType: "JSON",
                success: function(res) {
                    if (res.length > 0) {
                        for (let index = 0; index < res.length; index++) {
                            const element = res[index];
                            //assetManager.remove(element.src);
                            assetManager.add({
                                type: 'image',
                                src: element.src,
                                height: element.height,
                                width: element.width,
                            });
                        }
                    }
                }
            })
        });
        editor.render();
    </script>
    @php
        $page_content = json_decode(base64_decode($page_content));
    @endphp
    @if ($page_content != null && $page_content != '')
        <script>
            editor.setComponents(`{!! $page_content->body !!}`);
            editor.setStyles(`{!! $page_content->css !!}`);
            editor.setJs(`{!! $page_content->js !!}`);
        </script>
    @elseif ($page->page->layout == 0)
        <script>
            editor.setComponents('');
        </script>
    @elseif ($page->page->layout != 0)
        @php
            $template = getTemplateUri($layout);
            $file = file_get_contents($template . 'index.php');
        preg_match('/<body.*?>(.*?)<\/body>/si', $file, $match);
        $body = $match[1];
        @endphp
        <script>
            editor.setComponents(`{!! $body !!}`);
        </script>
    @endif
@endsection
