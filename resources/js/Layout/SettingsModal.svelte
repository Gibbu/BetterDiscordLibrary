<script>
  import {fade, fly} from 'svelte/transition';
  import Portal from 'svelte-portal';
  import Icon, {X, Sun, Moon} from 'svelte-hero-icons';
  import {createEventDispatcher} from 'svelte';
  const dispatch = createEventDispatcher();

  const close = () => {
    dispatch('close');
  }

  let theme = localStorage.theme;

  const setTheme = value => {
    theme = value;
    localStorage.theme = value;
    document.documentElement.setAttribute('class', value);
  }
</script>

<Portal target="#layers">
  <div class="bg-black bg-opacity-60 fixed inset-0 z-10" transition:fade={{duration: 200}} on:click={close}></div>
  <div
    class="bg-gray-200 dark:bg-gray-900 fixed top-[5vh] left-1/2 transform -translate-x-1/2 rounded shadow-xl overflow-hidden z-20 w-[500px]"
    in:fly={{y: 15, duration: 200}}
    out:fly={{y: -15, duration: 200}}
  >
    <header class="p-4 flex items-center justify-between">
      <h2 class="text-xl text-gray-800 dark:text-gray-100 font-display">Settings</h2>
      <button class="p-2 rounded hover:bg-gray-50 dark:hover:bg-gray-800 focus:outline-none" on:click={close}>
        <Icon src={X} class="w-5 h-5" />
      </button>
    </header>
    <div class="px-4 pb-4">
      <div>
        <p class="mb-2">Theme</p>
        <button
          class="flex items-center mb-2 w-full py-3 px-4 rounded border-2 focus:outline-none {theme === 'light' ? 'text-white bg-teal-500 border-teal-500' : 'border-gray-300 hover:border-gray-400 dark:border-gray-800 dark:hover:border-gray-700'}"
          on:click={() => setTheme('light')}
        >
          <Icon src={Sun} class="w-5 h-5 mr-3" /> Light
        </button>
        <button
          class="flex items-center w-full py-3 px-4 rounded border-2 focus:outline-none {theme === 'dark' ? 'text-white bg-teal-500 border-teal-500' : 'border-gray-300 hover:border-gray-400 dark:border-gray-800 dark:hover:border-gray-700'}"
          on:click={() => setTheme('dark')}
        >
          <Icon src={Moon} class="w-5 h-5 mr-3" /> Dark
        </button>
      </div>
    </div>
  </div>
</Portal>