<script>
  import {Inertia} from '@inertiajs/inertia';
  import {page} from '@inertiajs/inertia-svelte';
  import Icon, {Filter, Search, Eye, Download, Heart, MenuAlt1, Clock} from 'svelte-hero-icons';
  import {capitalize} from '$lib/helpers';

  import Layout from '$layout/Layout.svelte';
  import Card from '$components/Addons/Card.svelte';
  import Dropdown from '$components/Dropdown.svelte';

  // State
  export let addons;
  export let type;

  let query = '';
  let filtering = false;

  const filter = (column) => {
    Inertia.visit(`${type}?${column}=asc`, {
      only: ['addons'],
      onStart: () => {
        filtering = true;
      },
      onFinish: () => {
        filtering = false;
      }
    })
  }
</script>

<Layout title={capitalize(type)}>
  <header class="border-gray-300 dark:border-gray-800 my-12 pb-12 border-b flex items-center justify-between">
    <h1 class="text-gray-800 dark:text-white text-4xl font-display capitalize">{type}</h1>
    <div class="flex items-center">
      <div class="relative flex items-center mr-2">
        <Icon src={Search} class="w-5 h-5 absolute left-4 pointer-events-none" />
        <input type="text" class="input pl-12" bind:value={query} placeholder="Search all {addons.length} {type}...">
      </div>
      <Dropdown btn="btn btn-secondary" menu="right-0">
        <Icon src={Filter} class="w-5 h-5 mr-2" />
        <span>Filter</span>

        <svelte:fragment slot="menu">
          <button class="menu-item" on:click={() => filter('name')}>
            Name <Icon src={MenuAlt1} class="w-5 h-5" />
          </button>
          <button class="menu-item" on:click={() => filter('view_count')}>
            Views <Icon src={Eye} class="w-5 h-5" />
          </button>
          <button class="menu-item" on:click={() => filter('download_count')}>
            Downloads <Icon src={Download} class="w-5 h-5" />
          </button>
          <button class="menu-item" on:click={() => filter('like_count')}>
            Likes <Icon src={Heart} class="w-5 h-5" />
          </button>
          <button class="menu-item" on:click={() => filter('release')}>
            Release date <Icon src={Clock} class="w-5 h-5" />
          </button>
        </svelte:fragment>
      </Dropdown>
    </div>
  </header>
  <div class="my-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 {filtering ? 'opacity-20 pointer-events-none' : ''}">
    {#each addons as addon}
      {#if addon.name.toLowerCase().indexOf(query.toLowerCase()) !== -1}
        <Card {addon} />
      {/if}
    {/each}
  </div>
</Layout>