<div class="p-6 max-w-4xl mx-auto">
    @push('styles')
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.2.0/ckeditor5.css">
    @endpush

    {{-- Tombol kembali --}}
    <a wire:navigate href="{{ route('dashboard.posts.index') }}" class="ml-2 text-lg dark:text-gray-300">
        <h1 class="text-lg font-bold mb-6 mt-5 underline">
            <i class="fa-solid fa-arrow-left"></i> Back To Posts
        </h1>
    </a>

    <h4 class="text-2xl font-bold mb-6 mt-5">Edit Post</h4>

    <form wire:submit.prevent="update">
        {{-- Title --}}
        <div class="mb-4">
            <label class="block font-semibold mb-1">Title</label>
            <input type="text" wire:model="title" placeholder="Insert Title.."
                class="block w-full text-sm text-gray-900 border border-gray-600 rounded-lg bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
            @error('title') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        {{-- Thumbnail --}}
        <div class="mb-4">
            <label class="block font-semibold mb-1">Thumbnail</label>
            <input type="file" wire:model="thumbnail"
                class="block w-full text-sm text-gray-900 border border-gray-600 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
            @error('thumbnail') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror

            {{-- Preview gambar baru --}}
            @if ($thumbnail)
            <div class="flex justify-center mt-2">
                <figure class="max-w-xs">
                    <img class="h-100 w-auto rounded-md object-contain border border-gray-200 dark:border-gray-700 shadow-sm"
                        src="{{ $thumbnail->temporaryUrl() }}" alt="image description">
                </figure>
            </div>
            @elseif ($oldThumbnail)
            {{-- Preview gambar lama --}}
            <div class="flex justify-center mt-2">
                <figure class="max-w-xs">
                    <img class="h-100 w-auto rounded-md object-contain border border-gray-200 dark:border-gray-700 shadow-sm"
                        src="{{ asset('storage/' . $oldThumbnail) }}" alt="old thumbnail">
                </figure>
            </div>
            @endif
        </div>

        {{-- Categories --}}
        <div class="mb-4">
            <label class="block font-semibold mb-1">Categories</label>
            <div class="flex flex-wrap gap-3">
                @foreach($allCategories as $category)
                <label class="inline-flex items-center">
                    <input type="checkbox" wire:model="selectedCategories" value="{{ $category->id }}"
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2">{{ $category->name }}</span>
                </label>
                @endforeach
            </div>
            @error('selectedCategories') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        {{-- Tags --}}
        <div class="mb-4">
            <label class="block font-semibold mb-1">Tags</label>
            <div class="flex flex-wrap gap-3">
                @foreach($allTags as $tag)
                <label class="inline-flex items-center">
                    <input type="checkbox" wire:model="selectedTags" value="{{ $tag->id }}"
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2">{{ $tag->name }}</span>
                </label>
                @endforeach
            </div>
            @error('selectedTags') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        {{-- Content pakai CKEditor --}}
        <div class="mb-4" wire:ignore>
            <textarea id="editor">
                {!! $content !!}
            </textarea>
        </div>

        @error('content') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror

        <div class="flex justify-center">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                Update Post
            </button>
        </div>
    </form>
</div>

@push('scripts')

{{-- Script toast dan redirect --}}
<script>
    window.addEventListener('notify', event => {
        // Ambil index 0
        const payload = Array.isArray(event.detail) ? event.detail[0] : event.detail;
        const {
            type,
            message,
            redirect
        } = payload;

        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true,
            didOpen: (toastEl) => {
                toastEl.addEventListener('mouseenter', Swal.stopTimer);
                toastEl.addEventListener('mouseleave', Swal.resumeTimer);
            },
            willClose: () => {
                if (redirect) {
                    Livewire.navigate(redirect);
                }
            }
        });

        Toast.fire({
            icon: type,
            title: message
        });
    });

</script>


{{-- Script CkEditor --}}
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
    

    let uploadedImages = [];
    let debounceTimer;
    let editorInstance;

    ClassicEditor.create(document.querySelector('#editor'), editorConfig)
        .then(editor => {
            editorInstance = editor;

            // Set initial data dari Livewire
            editor.setData(@this.get('content') ?? '');

            // Upload Adapter aktif
            editor.plugins.get('FileRepository').createUploadAdapter = (loader) => new MyUploadAdapter(loader);

            // Deteksi perubahan konten dengan debounce
            editor.model.document.on('change:data', () => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    const content = editor.getData();
                    @this.set('content', content);

                    // Parse HTML untuk deteksi gambar yang masih ada
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(content, 'text/html');
                    const currentImages = Array.from(doc.querySelectorAll('img')).map(img => img.getAttribute('src'));

                    // Cari gambar yang sudah dihapus dari editor
                    const removedImages = uploadedImages.filter(url => !currentImages.includes(url));

                    if (removedImages.length > 0) {
                        removedImages.forEach(url => {
                            fetch('/ckeditor/delete', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({ url })
                            });
                        });

                        // Update daftar image aktif
                        uploadedImages = uploadedImages.filter(url => currentImages.includes(url));
                    }
                }, 500); // debounce 500ms
            });
        })
        .catch(error => {
            console.error('CKEditor initialization error:', error);
        });



    // Upload Image
    class MyUploadAdapter {
        constructor(loader) {
            this.loader = loader;
            this.url = '/ckeditor/upload';
        }

        upload() {
            return this.loader.file.then(file => new Promise((resolve, reject) => {
                const data = new FormData();
                data.append('upload', file);
                data.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                fetch(this.url, { method: 'POST', body: data })
                    .then(res => res.json())
                    .then(result => {
                        if (result.url) {
                            uploadedImages.push(result.url); // simpan image yang berhasil upload
                            resolve({ default: result.url });
                        } else {
                            reject(result.error?.message || 'Upload gagal');
                        }
                    })
                    .catch(err => reject(err));
            }));
        }

        abort() {}
    }




</script>
@endpush
