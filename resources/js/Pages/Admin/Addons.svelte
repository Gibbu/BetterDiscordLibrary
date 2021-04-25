<script>
  import {Inertia} from '@inertiajs/inertia';
  import {debounce} from 'debounce';
  import Icon, {ColorSwatch, Puzzle} from 'svelte-hero-icons';
  import Portal from 'svelte-portal';
  import {fade, fly} from 'svelte/transition';

  import AdminLayout from '$layout/Admin.svelte';
  import Spinner from '$components/Spinner.svelte';
  import Form from '$components/Addons/Form.svelte';

  // State
  export let addons;

  let query = '';
  const search = debounce(() => {
    Inertia.get('/admin/addons', {
      search: query
    }, {
      replace: true
    })
  }, 500)

  let formVisible = false;
  let selectedAddon;
  const openAddon = addon => {
    selectedAddon = addon;
    formVisible = !formVisible;
  }
</script>


<AdminLayout title="Admin">
  <div class="mb-4">
    <input type="text" class="input" bind:value={query} placeholder="Search addons..." on:keyup={search}>
  </div>
  <div class="bg-gray-50 dark:bg-gray-800 rounded overflow-hidden">
    <header class="grid grid-cols-[48px,1fr,1fr] gap-4 p-4 items-center border-b border-gray-200 dark:border-gray-700">
      <span class="text-xs text-gray-500 dark:text-gray-400">Type</span>
      <span class="text-xs text-gray-500 dark:text-gray-400">Name</span>
      <span class="text-xs text-gray-500 dark:text-gray-400">Developer</span>
    </header>
    {#each addons as addon}
      <button class="grid grid-cols-[48px,1fr,1fr] gap-4 p-4 w-full text-left items-center border-b border-gray-200 dark:border-gray-700 hover:bg-white dark:hover:bg-gray-700 last:border-0 focus:outline-none" on:click={() => openAddon(addon)}>
        <Icon src={(addon.type === 'theme' ? ColorSwatch : Puzzle)} class="w-6 h-6" />
        <span>{addon.name}</span>
        <span class="flex items-center">
          <img src={addon.user.avatar} alt="Addon developer avatar" class="w-8 h-8 rounded-full mr-2">
          {addon.user.name}
        </span>
      </button>
    {/each}
  </div>
</AdminLayout>

<Form data={selectedAddon} visible={formVisible} editing={true} on:close={() => formVisible = false} />