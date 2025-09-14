<div class="p-6 max-w-4xl mx-auto">
    @push('styles')
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.2.0/ckeditor5.css">
    <style>
        /* Tinggi default editor */
        .ck-editor__editable[role="textbox"] {
            min-height: 300px;
        }

        /* === Dark Mode Styling === */
        .dark .ck.ck-editor {
            background-color: #1f2937;
            /* bg gray-800 */
            color: #ffffff;
            /* text gray-50 */
            border-color: #374151;
            /* border gray-700 */
        }

        /* Area content */
        .dark .ck-editor__editable {
            background-color: #1f2937 !important;
            color: #ffffff !important;
        }

        /* Placeholder text */
        .dark .ck-editor__editable .ck-placeholder::before {
            color: #9ca3af !important;
            /* gray-400 */
        }

        /* Toolbar */
        .dark .ck.ck-toolbar {
            background-color: #374151 !important;
            /* gray-700 */
            border-color: #4b5563 !important;
            /* gray-600 */
        }

        .dark .ck.ck-toolbar .ck-button {
            color: #808a94 !important;
        }

        .dark .ck.ck-toolbar .ck-button:hover {
            background-color: #4b5563 !important;
        }

        /* Panel pop-up (misal link, image, table) */
        .dark .ck.ck-balloon-panel {
            background-color: #1f2937 !important;
            border-color: #7f848b !important;
            color: #c5cbcf !important;
        }

        /* Dropdown button caret biar putih */
        .dark .ck.ck-icon {
            fill: #7a8692 !important;
            color: #7a8692 !important;
        }

    </style>
    @endpush

    <a wire:navigate href="{{ route('dashboard.posts.index') }}" class="ml-2 text-sm text-gray-600">Back To Posts</a>
    <h1 class="text-2xl font-bold mb-6 mt-5">Create New Post</h1>

    <form wire:submit.prevent="store">
        {{-- Title --}}
        <div class="mb-4">
            <label class="block font-semibold mb-1">Title</label>
            <input type="text" wire:model.live.debounce.800ms="title"
                class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white">
            @error('title') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        {{-- Slug --}}
        <div class="mb-4">
            <label class="block font-semibold mb-1">Slug</label>
            <input type="text" wire:model="slug"
                class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white">
            @error('slug') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        {{-- Content pakai CKEditor --}}
        <div class="mb-6" wire:ignore>
            <label class="block font-semibold mb-1">Content</label>
            <textarea wire:model="content" id="editor" name="content"
                class="custom-editor w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white"></textarea>
            @error('content') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>


        <div class="flex justify-center">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                Save Post
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script type="importmap">
    {
            "imports": {
                "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/43.2.0/ckeditor5.js",
                "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/43.2.0/"
            }
        }
</script>

<script type="module">
    import {
		ClassicEditor,
		AccessibilityHelp,
		Alignment,
		Autoformat,
		AutoImage,
		AutoLink,
		Autosave,
		Base64UploadAdapter,
		BlockQuote,
		BlockToolbar,
		Bold,
		CloudServices,
		Code,
		CodeBlock,
		Essentials,
		FindAndReplace,
		FontBackgroundColor,
		FontColor,
		FontFamily,
		FontSize,
		GeneralHtmlSupport,
		Heading,
		Highlight,
		HorizontalLine,
		ImageBlock,
		ImageCaption,
		ImageInline,
		ImageInsert,
		ImageInsertViaUrl,
		ImageResize,
		ImageStyle,
		ImageTextAlternative,
		ImageToolbar,
		ImageUpload,
		Indent,
		IndentBlock,
		Italic,
		Link,
		LinkImage,
		List,
		ListProperties,
		MediaEmbed, // Tambahkan MediaEmbed di sini
		Paragraph,
		RemoveFormat,
		SelectAll,
		SourceEditing,
		SpecialCharacters,
		SpecialCharactersArrows,
		SpecialCharactersCurrency,
		SpecialCharactersEssentials,
		SpecialCharactersLatin,
		SpecialCharactersMathematical,
		SpecialCharactersText,
		Strikethrough,
		Style,
		Subscript,
		Superscript,
		Table,
		TableCaption,
		TableCellProperties,
		TableColumnResize,
		TableProperties,
		TableToolbar,
		TextTransformation,
		TodoList,
		Underline,
		Undo
	} from 'ckeditor5';
	
	const editorConfig = {
		toolbar: {
			items: [
				'undo',
				'redo',
				'|',
				'sourceEditing',
				'|',
				'heading',
				'style',
				'|',
				'fontSize',
				'fontFamily',
				'fontColor',
				'fontBackgroundColor',
				'|',
				'bold',
				'italic',
				'underline',
				'|',
				'link',
				'insertImage',
				'insertTable',
				'highlight',
				'blockQuote',
				'codeBlock',
				'|',
				'alignment',
				'|',
				'bulletedList',
				'numberedList',
				'todoList',
				'outdent',
				'indent',
				'removeFormat',
			],
			shouldNotGroupWhenFull: false
		},
		plugins: [
			AccessibilityHelp,
			Alignment,
			Autoformat,
			AutoImage,
			AutoLink,
			Autosave,
			Base64UploadAdapter,
			BlockQuote,
			BlockToolbar,
			Bold,
			CloudServices,
			Code,
			CodeBlock,
			Essentials,
			FindAndReplace,
			FontBackgroundColor,
			FontColor,
			FontFamily,
			FontSize,
			GeneralHtmlSupport,
			Heading,
			Highlight,
			HorizontalLine,
			ImageBlock,
			ImageCaption,
			ImageInline,
			ImageInsert,
			ImageInsertViaUrl,
			ImageResize,
			ImageStyle,
			ImageTextAlternative,
			ImageToolbar,
			ImageUpload,
			Indent,
			IndentBlock,
			Italic,
			Link,
			LinkImage,
			List,
			ListProperties,
			MediaEmbed, // Pastikan MediaEmbed ada di sini
			Paragraph,
			RemoveFormat,
			SelectAll,
			SourceEditing,
			SpecialCharacters,
			SpecialCharactersArrows,
			SpecialCharactersCurrency,
			SpecialCharactersEssentials,
			SpecialCharactersLatin,
			SpecialCharactersMathematical,
			SpecialCharactersText,
			Strikethrough,
			Style,
			Subscript,
			Superscript,
			Table,
			TableCaption,
			TableCellProperties,
			TableColumnResize,
			TableProperties,
			TableToolbar,
			TextTransformation,
			TodoList,
			Underline,
			Undo
		],
		blockToolbar: [
			'fontSize',
			'fontColor',
			'fontBackgroundColor',
			'|',
			'bold',
			'italic',
			'|',
			'link',
			'insertImage',
			'insertTable',
			'|',
			'bulletedList',
			'numberedList',
			'outdent',
			'indent'
		],
		fontFamily: {
			supportAllValues: true
		},
		fontSize: {
			options: [10, 12, 14, 'default', 18, 20, 22],
			supportAllValues: true
		},
		heading: {
			options: [
				{
					model: 'paragraph',
					title: 'Paragraph',
					class: 'ck-heading_paragraph'
				},
				{
					model: 'heading1',
					view: 'h1',
					title: 'Heading 1',
					class: 'ck-heading_heading1'
				},
				{
					model: 'heading2',
					view: 'h2',
					title: 'Heading 2',
					class: 'ck-heading_heading2'
				},
				{
					model: 'heading3',
					view: 'h3',
					title: 'Heading 3',
					class: 'ck-heading_heading3'
				},
				{
					model: 'heading4',
					view: 'h4',
					title: 'Heading 4',
					class: 'ck-heading_heading4'
				},
				{
					model: 'heading5',
					view: 'h5',
					title: 'Heading 5',
					class: 'ck-heading_heading5'
				},
				{
					model: 'heading6',
					view: 'h6',
					title: 'Heading 6',
					class: 'ck-heading_heading6'
				}
			]
		},
		htmlSupport: {
			allow: [
				{
					name: /^.*$/,
					styles: true,
					attributes: true,
					classes: true
				}
			]
		},
		image: {
			toolbar: [
				'toggleImageCaption',
				'imageTextAlternative',
				'|',
				'imageStyle:inline',
				'imageStyle:wrapText',
				'imageStyle:breakText',
				'|',
				'resizeImage'
			]
		},
		link: {
			addTargetToExternalLinks: true,
			defaultProtocol: 'https://',
			decorators: {
				toggleDownloadable: {
					mode: 'manual',
					label: 'Dapat diunduh',
					attributes: {
						download: 'file'
					}
				}
			}
		},
		list: {
			properties: {
				styles: true,
				startIndex: true,
				reversed: true
			}
		},
		menuBar: {
			isVisible: false
		},
		placeholder: 'Write content here..',
		style: {
			definitions: [
				{
					name: 'Article category',
					element: 'h3',
					classes: ['category']
				},
				{
					name: 'Title',
					element: 'h2',
					classes: ['document-title']
				},
				{
					name: 'Subtitle',
					element: 'h3',
					classes: ['document-subtitle']
				},
				{
					name: 'Info box',
					element: 'p',
					classes: ['info-box']
				},
				{
					name: 'Side quote',
					element: 'blockquote',
					classes: ['side-quote']
				},
				{
					name: 'Marker',
					element: 'span',
					classes: ['marker']
				},
				{
					name: 'Spoiler',
					element: 'span',
					classes: ['spoiler']
				},
				{
					name: 'Code (dark)',
					element: 'pre',
					classes: ['fancy-code', 'fancy-code-dark']
				},
				{
					name: 'Code (bright)',
					element: 'pre',
					classes: ['fancy-code', 'fancy-code-bright']
				}
			]
		},
		table: {
			contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells', 'tableProperties', 'tableCellProperties']
		}
	};

	let debounceTimer;

	ClassicEditor
		.create(document.querySelector('#editor'), editorConfig)
		.then(editor => {
			// Debounce ketika ada perubahan pada editor
			editor.model.document.on('change:data', () => {
				clearTimeout(debounceTimer);
				debounceTimer = setTimeout(() => {
					@this.set('content', editor.getData()); // Update Livewire property
				}, 800); // Debounce selama 800ms
			});
		})
		.catch(error => {
			console.error(error);
		});

</script>
@endpush
