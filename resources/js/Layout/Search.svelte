<script>
  import {onMount} from 'svelte';
  import axios from 'axios';
  import Icon, {Search, ColorSwatch, Puzzle} from 'svelte-hero-icons';
  import {debounce} from 'debounce';
  import {Inertia} from '@inertiajs/inertia';
  import {InertiaLink} from '@inertiajs/inertia-svelte';

  const icons = {
    theme: ColorSwatch,
    plugin: Puzzle
  }

  let input;
  let results = [];
  let resultsEl;
  let hovered = false;
  let visible = false;
  let index = 0;

  let term = '';

  const query = e => {
    const blacklist = ['ArrowUp', 'ArrowDown', 'Control', 'Shift', 'Alt'];

    if (blacklist.some(el => e.key === el)) return;
    if (term.length > 2) search();
    if (term.length === 0 && term === '') reset();
  }
  
  const search = debounce(async() => {
    const req = await axios.get(`/search?query=${term}`);
    results = req.data;
  }, 300)

  const actions = e => {
    if (resultsEl) {
      if (e.key === 'Enter') open();
      if (e.key === 'ArrowUp') {
        e.preventDefault();
        if (index > 0) {
          index--;
        } else {
          index = results.length - 1;
        }
        scroll();
      }
      if (e.key === 'ArrowDown') {
        e.preventDefault();
        if (index < results.length - 1) {
          index++;
        } else {
          index = 0;
        }
        scroll();
      }
    }
  }

  const open = () => {
    if (results[index]) {
      const selected = results[index];
      Inertia.get(`/${selected.type}s/${selected.name}`);

      input.blur();
      reset();
    }
  }

  const searchKeys = e => {
    if (e.key === '/' && document.activeElement.tagName !== 'INPUT') {
      e.preventDefault();
      input.focus();
    }
    if (e.key === 'Escape') reset();
  }

  const scroll = () => {
    resultsEl.children[index].scrollIntoView({block: 'nearest'});
  }

  const hideResults = () => {
    if (!hovered) visible = false;
  }

  const reset = () => {
    term = '';
    results = [];
    index = 0;
  }

  onMount(() => window.addEventListener('keydown', searchKeys));
</script>

<div class="max-w-screen-sm w-full relative">
  <div class="flex items-center relative">
    <input
      type="text"
      class="input w-full pl-12"
      placeholder="Search... (Press '/' to focus)"
      bind:this={input}
      bind:value={term}
      on:keydown={actions}
      on:keyup={query}
      on:focus={() => visible = true}
      on:blur={hideResults}
    >
    <Icon src={Search} class="w-5 h-5 absolute left-4 pointer-events-none opacity-50" />
  </div>
  {#if visible && term.length > 2}
    <div
      id="global-results"
      bind:this={resultsEl}
      on:mouseenter={() => hovered = true}
      on:mouseleave={() => hovered = false}
      class="bg-gray-50 dark:bg-gray-950 border border-gray-300 dark:border-gray-800 px-4 pt-4 shadow-xl rounded overflow-y-auto max-h-[65vh] absolute top-full w-full transform translate-y-2"
    >
      {#if results.length > 0}
        {#each results as result, i}
          <button
            href="{result.type}s/{result.name}"
            class="rounded flex items-center py-3 px-4 w-full mb-2 text-left focus:outline-none {index === i ? 'bg-teal-500 text-white' : 'bg-gray-200 dark:bg-gray-800'} last:mb-4"
            on:mouseenter={() => index = i}
            on:click={open}
          >
            <Icon src={icons[result.type]} class="w-8 h-8 mr-4" />
            <div class="flex-1">
              <h4 class="font-display {index === i ? 'text-white' : 'text-gray-800 dark:text-white'}">{result.name}</h4>
              <p class="text-sm -mt-1 font-semibold {index !== i ? 'opacity-70' : ''}">by {result.user.name}</p>
            </div>
          </button>
        {/each}
      {:else}
        <p class="mb-4 text-sm opacity-60">Nothing found...</p>
      {/if}
    </div>
  {/if}
</div>