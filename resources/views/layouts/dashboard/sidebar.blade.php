 <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700" aria-label="Sidebar">
    <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
       <ul class="space-y-2 font-medium">
          <li>
             <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
               <i class="fa-solid fa-chart-line"></i>
                <span class="ms-3">Dashboard</span>
             </a>
          </li>
          <li>
            <button type="button" class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700" aria-controls="dropdown-example" data-collapse-toggle="dropdown-example">
              <i class="fa-regular fa-pen-to-square"></i>
                  <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Posts</span>
              <i class="fa-solid fa-sort-down"></i>
            </button>
            <ul id="dropdown-example" class="{{ request()->routeIs('tags') || request()->routeIs('categories') ? '' : 'hidden' }} py-2 space-y-2">
                  <li>
                     <a href="#" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"><i class="fa-solid fa-arrow-right"></i>Manage Posts</a>
                  </li>
                  <li>
                     <a wire:navigate href="{{ route('categories') }}"
                        class="{{ request()->routeIs('categories') ? 'dark:bg-gray-600' : '' }} flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                        <i class="fa-solid fa-arrow-right"></i>
                        Manage Categories
                     </a>
                   </li>
                  <li>
                    <a wire:navigate href="{{ route('tags') }}"
                       class="{{ request()->routeIs('tags') ? 'dark:bg-gray-600' : '' }} flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                       <i class="fa-solid fa-arrow-right"></i>
                       Manage Tags
                    </a>
                  </li>
                  
            </ul>
         </li>
       </ul>
    </div>
 </aside>
 
 
 

 