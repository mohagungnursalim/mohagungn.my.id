<div class="p-6 max-w-4xl mx-auto">
    @push('styles')
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.2.0/ckeditor5.css">
    @endpush

    <a wire:navigate href="{{ route('dashboard.posts.index') }}" class="ml-2 text-lg dark:text-gray-300">
        <h1 class="text-lg font-bold mb-6 mt-5 underline"><i class="fa-solid fa-arrow-left"></i> Back To Posts</h1>
    </a>
    <h4 class="text-2xl font-bold mb-6 mt-5">Create New Post</h4>

    <form wire:submit.prevent="store">
        {{-- Title --}}
        <div class="mb-4">
            <label class="block font-semibold mb-1">Title</label>
            <input type="text" wire:model="title" placeholder="Insert Title.."
                class="block w-full text-sm text-gray-900 border border-gray-600 rounded bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
            @error('title') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        {{-- Thumbnail --}}
        <div class="mb-4">
            <label class="block font-semibold mb-1">Thumbnail</label>
            <input type="file" wire:model="thumbnail"
                class="block w-full text-sm text-gray-900 border border-gray-600 rounded cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
            @error('thumbnail') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror

            @if ($thumbnail)
            <div class="flex justify-center mt-2">
                <figure class="max-w-xs">
                    <img class="h-100 w-auto rounded-md object-contain border border-gray-200 dark:border-gray-700 shadow-sm"
                        src="{{ $thumbnail->temporaryUrl() }}" alt="image description">
                </figure>
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Image Description</label>
                <input type="text" wire:model="image_description" placeholder="Insert Image Description.."
                    class="block w-full text-sm text-gray-900 border border-gray-600 rounded bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                @error('image_description') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            @endif
        </div>

        {{-- Categories --}}
        <div class="mb-4" wire:ignore>
            <label class="block font-semibold mb-1">Categories</label>
            <select id="categoriesSelect" multiple placeholder="Pilih kategori..."></select>
            @error('selectedCategories') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>
        
        {{-- Tags --}}
        <div class="mb-4" wire:ignore>
            <label class="block font-semibold mb-1">Tags</label>
            <select id="tagsSelect" multiple placeholder="Pilih tag..."></select>
            @error('selectedTags') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>


        {{-- Content CKEditor --}}
        <div class="mb-4" wire:ignore>
            <label class="block font-semibold mb-1">Content</label>
            <textarea wire:model="content" id="editor" name="content"
                class="custom-editor w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white"></textarea>
        </div>
        @error('content') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror

        <div class="flex justify-center">
            <!-- Tombol Save -->
              <button 
                  type="submit"
                  wire:target="store"
                  wire:loading.attr="disabled"
                  class="px-4 py-2 w-32 text-white bg-blue-500 rounded-full disabled hover:bg-blue-600">
                  <span wire:loading.remove wire:target="store">Save</span>
                  <span wire:loading wire:target="store" class="flex items-center justify-center">
                      <i class="fas fa-spinner fa-spin"></i>
                  </span>
              </button>
        </div>

    </form>

</div>

@push('scripts')

{{-- TomSelect Create Post --}}
<script>
    // Global observer untuk cleanup
    let observer;
    
    // Cleanup function untuk mencegah memory leaks
    function cleanup() {
        if (observer) {
            observer.disconnect();
            observer = null;
        }
        document.querySelectorAll('#categoriesSelect, #tagsSelect').forEach(el => {
            if (el.tomselect) {
                el.tomselect.destroy();
            }
        });
    }
    
    // Debounce function untuk mengoptimalkan performa
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    // Event listener dengan cleanup
    document.addEventListener('livewire:navigated', () => {
            setTimeout(() => { 
                cleanup();
                initTomSelect('categoriesSelect', @this, 'selectedCategories', '/api/categories', false);
                initTomSelect('tagsSelect', @this, 'selectedTags', '/api/tags', true);
            }, 100);
    });
    
    function initTomSelect(id, livewire, model, url, allowCreate = false) {
        const el = document.getElementById(id);
        if (!el) {
            console.warn(`Element with id ${id} not found`);
            return;
        }
    
        // Validasi CSRF Token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        if (!csrfToken) {
            console.error('CSRF token not found');
            return;
        }
    
        // Simpan nilai sebelumnya jika ada
        const previousValue = el.tomselect ? el.tomselect.getValue() : null;
    
        if (el.tomselect) el.tomselect.destroy();
    
        let nextPage = null;
        let isLoading = false;
    
        const select = new TomSelect(el, {
            valueField: 'id',
            labelField: 'name',
            searchField: 'name',
            plugins: ['remove_button', 'clear_button'],
            preload: true,
    
            // Konfigurasi pembuatan tag baru
            create: allowCreate ? function(input, callback) {
                if (isLoading) return;
                isLoading = true;
            
                fetch('/api/tags', { // pastikan ini endpoint POST ke storeTag
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ name: input })
                })
                .then(res => {
                    if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
                    return res.json();
                })
                .then(json => {
                    if (json.success && json.item) {
                        const newItem = { id: json.item.id, name: json.item.name };
                        // Tambahkan ke option
                        select.addOption(newItem);
                        // Langsung pilih item baru
                        select.addItem(newItem.id);
                        // Trigger ke Livewire
                        livewire.set(model, select.getValue());
                        // Callback ke TomSelect
                        callback(newItem);
                    } else {
                        console.warn('Failed to create item:', json);
                        callback();
                    }
                })
                .catch(error => {
                    console.error('Failed to create item:', error);
                    callback();
                })
                .finally(() => {
                    isLoading = false;
                });
            } : false,

    
            // Konfigurasi loading data
            load: function(query, callback) {
                if (isLoading) return;
                isLoading = true;
    
                const endpoint = `${url}?search=${encodeURIComponent(query || '')}&page=1`;
                select.settings.render.loading_more();
    
                fetch(endpoint)
                    .then(res => {
                        if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
                        return res.json();
                    })
                    .then(json => {
                        nextPage = json.next_page_url || null;
                        callback(json.data || []);
                    })
                    .catch((error) => {
                        console.error('Failed to load data:', error);
                        callback([]);
                    })
                    .finally(() => {
                        isLoading = false;
                    });
            },
    
            // Template rendering
            render: {
                loading: function() {
                    
                    return '<div class="loading ml-2">Searching <i class="fas fa-spinner fa-spin text-gray-400 dark:text-white text-xs"></i></div>';
                },
                loading_more: function() {
                    return '<div class="loading-more">Loading more results...</div>';
                },
                no_results: function() {
                    return '<div class="no-results">No results</div>';
                },
                option: function(data, escape) {
                    return `<div class="py-2 px-3">${escape(data.name)}</div>`;
                }
            }
        });
    
        // Kembalikan nilai sebelumnya jika ada
        if (previousValue) {
            select.setValue(previousValue);
        }
    
        // Livewire binding
        select.on('change', () => {
            if (!isLoading) {
                livewire.set(model, select.getValue());
            }
        });
    
        // Handle dropdown open dengan debounce
        select.on('dropdown_open', debounce(() => {
            setupLoadMoreObserver();
        }, 100)); //300ms
    
        function setupLoadMoreObserver() {
            const dropdown = select.dropdown_content;
            if (!dropdown) return;
    
            // Hapus tombol load more yang ada
            dropdown.querySelectorAll('.ts-load-more').forEach(e => e.remove());
    
            if (!nextPage) return;
    
            const btn = document.createElement('div');
            btn.className =
                'ts-load-more text-center py-2 text-blue-500 cursor-pointer border-t border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800';
            btn.textContent = 'Load more...';
            btn.style.userSelect = 'none';
    
            const loadMoreHandler = debounce(async (e) => {
                if (isLoading) return;
                
                e.stopPropagation();
                btn.textContent = 'Loading...';
                btn.classList.add('opacity-70', 'pointer-events-none');
                isLoading = true;
    
                try {
                    const res = await fetch(nextPage);
                    if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
                    
                    const json = await res.json();
                    nextPage = json.next_page_url || null;
    
                    (json.data || []).forEach(item => select.addOption(item));
    
                    // Refresh dropdown tanpa menutupnya
                    select.refreshOptions(false);
                    select.open();
    
                    // Setup ulang observer
                    setupLoadMoreObserver();
    
                } catch (error) {
                    console.error('Failed to load more:', error);
                    btn.textContent = 'Load more...';
                    btn.classList.remove('opacity-70', 'pointer-events-none');
                } finally {
                    isLoading = false;
                }
            }, 100); //300ms
    
            btn.addEventListener('click', loadMoreHandler);
            dropdown.appendChild(btn);
    
            // Setup observer untuk menjaga tombol tetap terlihat
            if (observer) {
                observer.disconnect();
            }
    
            observer = new MutationObserver(() => {
                if (!dropdown.contains(btn) && nextPage) {
                    dropdown.appendChild(btn);
                }
            });
    
            observer.observe(dropdown, { childList: true });
        }
    
        // Cleanup saat halaman unload
        window.addEventListener('unload', cleanup);
    }
</script>


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
	let uploadedImages = [];
	
	ClassicEditor.create(document.querySelector('#editor'), editorConfig)
    .then(editor => {
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

            }, 800); // debounce 800ms
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

				fetch(this.url, {
					method: 'POST',
					body: data
				})
				.then(res => res.json())
				.then(result => {
					if (result.url) {
						uploadedImages.push(result.url); // ✅ simpan list image yang diupload
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
