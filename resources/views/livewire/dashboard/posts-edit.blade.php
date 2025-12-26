<div class="p-6 max-w-4xl mx-auto">
    @push('styles')
    <style>
        /* ====== TOM SELECT STYLE ====== */
        
        .ts-wrapper {
            width: 100%;
            font-size: 0.875rem; /* text-sm */
        }
        
        /* Input utama */
        .ts-control {
            display: block;
            width: 100%;
            font-size: 0.875rem;
            color: rgb(17 24 39); /* text-gray-900 */
            border: 1px solid rgb(209 213 219); /* border-gray-300 */
            border-radius: 0.375rem; /* rounded-md */
            background-color: rgb(249 250 251); /* bg-gray-50 */
            transition: all 0.2s ease-in-out;
            min-height: 38px;
            padding: 6px 8px;
        }
        
        .dark .ts-control {
            color: rgb(156 163 175); /* dark:text-gray-400 */
            border-color: rgb(209 213 219); /* dark:border-gray-300 */
            background-color: rgb(55 65 81); /* dark:bg-gray-700 */
        }
        
        .ts-control:focus,
        .ts-control.focus {
            outline: none;
            border-color: rgb(59 130 246); /* blue-500 */
            box-shadow: 0 0 0 1px rgb(59 130 246);
        }
        
        /* Placeholder lightmode */
        .ts-control input {
            color: rgb(17 24 39); /* gray-900 */
            background-color: rgb(249 250 251); /* gray-50 */
        }
        
        /* Placeholder darkmode */
        .dark .ts-control input {
            color: rgb(229 231 235); /* gray-200 */
            background-color: rgb(55 65 81); /* gray-700 */
        }
    
        
        /* Selected items */
        .ts-control .item {
            background-color: rgb(229 231 235); /* bg-gray-200 */
            color: rgb(31 41 55); /* text-gray-800 */
            border-radius: 0.25rem;
            padding: 2px 6px;
            margin-right: 4px;
        }
        
        .dark .ts-control .item {
            background-color: rgb(75 85 99); /* dark:bg-gray-600 */
            color: rgb(243 244 246); /* dark:text-gray-100 */
        }
        
        /* Dropdown */
        .ts-dropdown {
            background-color: rgb(255 255 255); /* bg-white */
            border: 1px solid rgb(209 213 219); /* border-gray-300 */
            border-radius: 0.375rem;
            color: rgb(17 24 39); /* text-gray-900 */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .dark .ts-dropdown {
            background-color: rgb(55 65 81); /* dark:bg-gray-700 */
            border-color: rgb(209 213 219); /* dark:border-gray-300 */
            color: rgb(243 244 246); /* dark:text-gray-100 */
        }
        
        /* Hover item */
        .ts-dropdown .option:hover {
            background-color: rgb(59 130 246); /* bg-blue-500 */
            color: white;
            cursor: pointer;
        }
        
        /* Selected in dropdown */
        .ts-dropdown .option.selected {
            background-color: rgb(37 99 235); /* blue-600 */
            color: white;
        }
        
        /* No result / loading */
        .ts-dropdown .loading,
        .ts-dropdown .no-results {
            color: rgb(156 163 175); /* text-gray-400 */
            text-align: center;
            padding: 0.5rem 0;
        }
        
        /* Clear & remove buttons */
        .ts-wrapper .clear-button,
        .ts-wrapper .remove {
            color: rgb(107 114 128); /* gray-500 */
        }
        
        .dark .ts-wrapper .clear-button,
        .dark .ts-wrapper .remove {
            color: rgb(156 163 175); /* dark:gray-400 */
        }
        
        .ts-wrapper .clear-button:hover,
        .ts-wrapper .remove:hover {
            color: rgb(59 130 246); /* hover:blue-500 */
        }
    </style>
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
                class="block w-full text-sm text-gray-900 border border-gray-300 rounded dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-300 dark:placeholder-gray-200">
                    @error('title')
                        <div class="text-red-500 text-sm">
                            {{ $message }}
                        </div>
                    @enderror
        </div>

        {{-- Thumbnail Upload --}}
        <div 
            x-cloak x-transition x-data="{ progress: 0 }"
            x-on:livewire-upload-start="progress = 0"
            x-on:livewire-upload-finish="progress = 0"
            x-on:livewire-upload-error="progress = 0"
            x-on:livewire-upload-progress="progress = $event.detail.progress"
            class="mb-4">
        
            <label class="block font-semibold mb-1">Thumbnail</label>
        
            {{-- Upload Input --}}
            <input 
                wire:model="thumbnail" 
                type="file" 
                accept="image/*"
                class="w-full p-1 mb-2 border border-gray-300 rounded dark:bg-gray-700 dark:text-white" 
            />
            @error('thumbnail')
                <div class="text-red-500 text-sm">
                    {{ $message }}
                </div>
            @enderror
        
            {{-- Progress Bar --}}
            <div x-show="progress > 0" class="w-full mb-4 bg-gray-300 rounded">
                <div 
                    class="h-2 rounded transition-all duration-200"
                    :class="progress < 100 ? 'bg-blue-600' : 'bg-green-600'"
                    :style="'width: ' + progress + '%'"
                ></div>
            </div>
        
            {{-- Preview Thumbnail Baru --}}
            @if ($thumbnail)
            <div class="mb-4 flex justify-center">
                <div class="relative inline-block">
                    <img 
                        src="{{ $thumbnail->temporaryUrl() }}" 
                        class="w-32 h-32 object-cover rounded border"
                        wire:loading.class="opacity-50"
                    />
        
                    {{-- Tombol Hapus --}}
                    <button 
                        type="button" 
                        wire:click="removeThumbnail" 
                        wire:target="removeThumbnail"
                        wire:loading.attr="disabled"
                        class="absolute top-0 right-0 transform translate-x-1/3 -translate-y-1/3 bg-red-500 hover:bg-red-600 text-white p-1 rounded-full shadow"
                        title="Remove Thumbnail"
                    >
                        <i class="fas fa-times"></i>
                    </button>
                </div>
        
                {{-- Thumbnail Description --}}
                    <textarea
                        wire:model="thumbnail_description"
                        placeholder="Describe the thumbnail..."
                        rows="3"
                        class="block w-full text-sm text-gray-500 border border-gray-300 rounded dark:bg-gray-700 dark:text-gray-200 px-3 py-2"
                    ></textarea>
        
                    @error('thumbnail_description')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
            </div>
            @endif
        
        
            {{-- Preview Thumbnail Lama (jika belum upload baru)--}}
            @if (!$thumbnail && $oldThumbnail)
            <div class="mb-4 flex justify-center">
                <div class="relative inline-block">
                    <img 
                        src="{{ asset('storage/'.$oldThumbnail) }}" 
                        class="w-32 h-32 object-cover rounded border"
                    />
        
                    <button 
                        type="button" 
                        wire:click="removeThumbnail"
                        class="absolute top-0 right-0 transform translate-x-1/3 -translate-y-1/3 bg-red-500 text-white p-1 rounded-full"
                    >
                        <i class="fas fa-times"></i>
                    </button>
                </div>
        
                {{-- Textarea Description --}}
                    <textarea
                        wire:model="thumbnail_description"
                        placeholder="Describe the thumbnail..."
                        rows="3"
                        class="block w-full text-sm text-gray-500 border border-gray-300 rounded dark:bg-gray-700 dark:text-gray-200 px-3 py-2"
                    ></textarea>
        
            </div>
                    @error('thumbnail_description')
                        <div class="text-red-500 text-sm">
                            {{ $message }}
                        </div>
                    @enderror
            @endif
        
        
            {{-- Loading Status --}}
            <div wire:loading wire:target="thumbnail" class="text-blue-500 flex items-center gap-2">
                <span>Uploading thumbnail...</span>
                <i class="fas fa-spinner fa-spin"></i>
            </div>
        
            <div wire:loading wire:target="removeThumbnail" class="text-blue-500 flex items-center gap-2">
                <span>Removing thumbnail...</span>
                <i class="fas fa-spinner fa-spin"></i>
            </div>
        
        </div>


        {{-- Categories --}}
        <div class="mb-4">
            <div wire:ignore>
                <label class="block font-semibold mb-1">Categories</label>
                <select 
                    class="block w-full text-sm text-gray-900 rounded bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:placeholder-gray-200" 
                    id="categoriesSelect" multiple placeholder="Select Categories...">
                </select>
            </div>
                @error('selectedCategories')
                    <div class="text-red-500 text-sm">
                        {{ $message }}
                    </div>
                @enderror
        </div>
        
        {{-- Tags --}}
        <div class="mb-4">
            <div wire:ignore>
                <label class="block font-semibold mb-1">Tags</label>
                <select 
                    class="block w-full text-sm text-gray-900 rounded bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:placeholder-gray-200" 
                    id="tagsSelect" multiple placeholder="Select Tags...">
                </select>
            </div>
                @error('selectedTags')
                    <div class="text-red-500 text-sm">
                        {{ $message }}
                    </div>
                @enderror
        </div>


        {{-- Content CKEditor form edit --}}
        <div class="mb-4" wire:ignore>
            <label class="block font-semibold mb-1">Content</label>
            <textarea 
                wire:model="content" 
                id="editor"
                name="content"
                class="custom-editor w-full border rounded px-3 py-2 dark:bg-gray-700 dark:placeholder-gray-200"
            >{!! $content !!}</textarea>
        </div>

        @error('content')
            <div class="text-red-500 text-sm">
                {{ $message }}
            </div>
        @enderror

        {{-- Tombol Save --}}
        <div class="flex gap-3 justify-center">
            <button 
                type="button"
                wire:click="updateDraft"
                wire:target="updateDraft"
                wire:loading.attr="disabled"
                class="px-4 py-2 text-white bg-gray-600 rounded-full hover:bg-gray-700 transition"
            >
                <span wire:loading.remove wire:target="updateDraft">Update & Draft</span>
                <span wire:loading wire:target="updateDraft" class="flex items-center justify-center">
                    <i class="fas fa-spinner fa-spin"></i>
                </span>
            </button>
            
            <button 
                type="button"
                wire:click="updateNow"
                wire:target="updateNow"
                wire:loading.attr="disabled"
                class="px-4 py-2 text-white bg-green-600 rounded-full hover:bg-green-700 transition"
            >
                <span wire:loading.remove wire:target="updateNow">Update & Publish</span>
                <span wire:loading wire:target="updateNow" class="flex items-center justify-center">
                    <i class="fas fa-spinner fa-spin"></i>
                </span>
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


{{-- TomSelect Script --}}
<script>
    document.addEventListener('livewire:navigated', () => {
        setTimeout(() => {
            cleanup();
            initTomSelect(
                'categoriesSelect',
                @this,
                'selectedCategories',
                '/api/categories',
                false,
                @js($existingCategories)
            );
            initTomSelect(
                'tagsSelect',
                @this,
                'selectedTags',
                '/api/tags',
                true,
                @js($existingTags)
            );
        }, 150);
    });
    
    // Menggunakan Map untuk menyimpan observer untuk setiap select
    const observers = new Map();
    
    function cleanup() {
        // Membersihkan semua observer
        observers.forEach(observer => observer.disconnect());
        observers.clear();
        
        document.querySelectorAll('#categoriesSelect, #tagsSelect').forEach(el => {
            if (el.tomselect) el.tomselect.destroy();
        });
    }
    
    function debounce(fn, delay) {
        let timer;
        return (...args) => {
            clearTimeout(timer);
            timer = setTimeout(() => fn(...args), delay);
        };
    }
    
    function initTomSelect(id, livewire, model, url, allowCreate = false, existingData = []) {
        const el = document.getElementById(id);
        if (!el) return;
    
        const csrf = document.querySelector('meta[name="csrf-token"]').content;
        if (el.tomselect) el.tomselect.destroy();
    
        let nextPage = null;
        let isLoading = false;
        let loadMoreBtn = null; // Menyimpan referensi tombol load more
    
        const select = new TomSelect(el, {
            valueField: 'id',
            labelField: 'name',
            searchField: 'name',
            options: existingData,
            items: existingData.map(item => item.id),
            preload: true,
            plugins: ['remove_button', 'clear_button'],
    
            create: allowCreate
                ? (input, cb) => {
                    fetch('/api/tags', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf
                        },
                        body: JSON.stringify({ name: input })
                    })
                        .then(res => res.json())
                        .then(json => {
                            if (json.success && json.item) {
                                select.addOption(json.item);
                                select.addItem(json.item.id);
                                livewire.set(model, select.getValue());
                                cb(json.item);
                            } else cb();
                        })
                        .catch(() => cb());
                }
                : false,
    
            load: (query, cb) => {
                if (isLoading) return;
                isLoading = true;
                fetch(`${url}?search=${encodeURIComponent(query)}&page=1`)
                    .then(res => res.json())
                    .then(json => {
                        nextPage = json.next_page_url || null;
                        cb(json.data || []);
                    })
                    .catch(() => cb([]))
                    .finally(() => (isLoading = false));
            },
    
            render: {
                option: (data, escape) => `<div class="px-3 py-2">${escape(data.name)}</div>`,
                no_results: () => `<div class="text-gray-500 p-2">No results</div>`,
                loading: () => `<div class="text-gray-400 p-2">Loading...</div>`
            }
        });
    
        select.on('change', () => livewire.set(model, select.getValue()));
    
        // Bersihkan observer yang ada saat dropdown ditutup
        select.on('dropdown_close', () => {
            if (observers.has(id)) {
                observers.get(id).disconnect();
                observers.delete(id);
            }
            // Hapus tombol load more yang ada
            if (loadMoreBtn && loadMoreBtn.parentNode) {
                loadMoreBtn.remove();
            }
            loadMoreBtn = null;
        });
    
        select.on('dropdown_open', debounce(() => setupLoadMore(select, id), 200));
    
        function setupLoadMore(select, selectId) {
            const dropdown = select.dropdown_content;
            if (!dropdown || !nextPage) return;
    
            // Hapus tombol load more yang sudah ada jika ada
            if (loadMoreBtn && loadMoreBtn.parentNode) {
                loadMoreBtn.remove();
            }
    
            // Buat tombol load more baru
            loadMoreBtn = document.createElement('div');
            loadMoreBtn.className =
                'ts-load-more text-center py-2 text-blue-500 cursor-pointer border-t hover:bg-gray-100';
            loadMoreBtn.textContent = 'Load more...';
    
            loadMoreBtn.addEventListener(
                'click',
                debounce(async () => {
                    if (isLoading) return;
                    isLoading = true;
                    loadMoreBtn.textContent = 'Loading...';
    
                    try {
                        const res = await fetch(nextPage);
                        const json = await res.json();
                        nextPage = json.next_page_url || null;
                        (json.data || []).forEach(item => select.addOption(item));
                        select.refreshOptions(false);
                        
                        // Update tombol load more
                        if (!nextPage) {
                            loadMoreBtn.textContent = 'No more data';
                            loadMoreBtn.classList.add('opacity-50', 'cursor-not-allowed');
                        } else {
                            loadMoreBtn.textContent = 'Load more...';
                        }
                    } catch (error) {
                        console.error('Error loading more:', error);
                        loadMoreBtn.textContent = 'Error loading more';
                    } finally {
                        isLoading = false;
                    }
                }, 200)
            );
    
            dropdown.appendChild(loadMoreBtn);
    
            // Buat observer baru dan simpan di Map
            if (observers.has(selectId)) {
                observers.get(selectId).disconnect();
            }
    
            const observer = new MutationObserver(() => {
                if (!dropdown.contains(loadMoreBtn) && nextPage) {
                    dropdown.appendChild(loadMoreBtn);
                }
            });
    
            observer.observe(dropdown, { childList: true });
            observers.set(selectId, observer);
        }
    }
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
    

	
	let debounceTimer;
    let uploadedImages = [];
    let editorInstance;

    ClassicEditor.create(document.querySelector('#editor'), editorConfig)
        .then(editor => {
            editorInstance = editor;

            // Upload Adapter
            editor.plugins.get('FileRepository').createUploadAdapter =
                (loader) => new MyUploadAdapter(loader);

            // Detect perubahan
            editor.model.document.on('change:data', () => {
                clearTimeout(debounceTimer);

                debounceTimer = setTimeout(() => {
                    const content = editor.getData();
                    @this.set('content', content);

                    // Scan image
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(content, 'text/html');
                    const currentImages =
                        Array.from(doc.querySelectorAll('img'))
                        .map(img => img.getAttribute('src'));

                    // Cari yang dihapus
                    const removedImages = uploadedImages.filter(
                        url => !currentImages.includes(url)
                    );

                    if (removedImages.length > 0) {
                        removedImages.forEach(url => {
                            fetch('/ckeditor/delete', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN':
                                        document.querySelector('meta[name="csrf-token"]').content
                                },
                                body: JSON.stringify({ url })
                            });
                        });

                        uploadedImages = uploadedImages.filter(
                            url => currentImages.includes(url)
                        );
                    }

                }, 800);
            });

        })
        .catch(error => console.error('CKEditor init error:', error));


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
