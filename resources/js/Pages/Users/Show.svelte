<script>
  import Icon, {Code, Cog} from 'svelte-hero-icons';
  import tooltip from '$lib/tooltip';

  import Layout from '$layout/Layout.svelte';

  import Card from '$components/Addons/Card.svelte';

  // State
  export let user;
  export let themes;
  export let plugins;
  export let likes;

  $: active = (window.location.hash ? window.location.hash.replace('#','') : (user.roles.includes('dev') ? (themes.length > 0 ? 'themes' : 'plugins') : 'likes'));
  const setActive = page => {
    window.location.hash = page;
    active = page;
  }
</script>

<Layout title="{user.slug}">
  <header class="bg-gray-50 dark:bg-gray-950 py-12 flex items-center border-b border-gray-300 dark:border-gray-800">
    <div class="wrap flex items-center justify-between">
      <div class="flex items-center">
        <img src={user.avatar} alt="User avatar" class="w-24 h-24 rounded-full mr-6">
        <div>
          <h1 class="font-display flex items-center text-gray-800 dark:text-white text-3xl">{user.name}</h1>
          {#if user.roles.length > 0}
            <div class="flex items-center mt-2">
              {#if user.roles.includes('dev')}
                <span use:tooltip={{content: 'Addon developer', delay: [250, 0]}} class="mr-3">
                  <Icon src={Code} class="w-6 h-6" />
                </span>
              {/if}
              {#if user.roles.includes('admin')}
                <span use:tooltip={{content: 'Website admin', delay: [250, 0]}} class="mr-3">
                  <Icon src={Cog} class="w-6 h-6" />
                </span>
              {/if}
            </div>
          {/if}
        </div>
      </div>
      <div class="flex">
        {#if user.roles.includes('dev')}
          <button
            class="btn mr-2 {active === 'themes' ? 'btn-primary bg-teal-500 hover:bg-teal-400 focus:bg-teal-400 focus:ring-teal-500 focus:ring-opacity-40' : 'btn-secondary'}"
            on:click={() => setActive('themes')}
          >
            Themes
            <span class="ml-1 {active === 'themes' ? '' : 'opacity-60'}">({themes.length})</span>
          </button>
          <button
            class="btn mr-2 {active === 'plugins' ? 'btn-primary bg-teal-500 hover:bg-teal-400 focus:bg-teal-400 focus:ring-teal-500 focus:ring-opacity-40' : 'btn-secondary'}"
            on:click={() => setActive('plugins')}
          >
            Plugins
            <span class="ml-1 {active === 'plugins' ? '' : 'opacity-60'}">({plugins.length})</span>
          </button>
        {/if}
        <button
          class="btn {active === 'likes' ? 'btn-primary bg-teal-500 hover:bg-teal-400 focus:bg-teal-400 focus:ring-teal-500 focus:ring-opacity-40' : 'btn-secondary'}"
          on:click={() => setActive('likes')}
        >
          Likes
          <span class="ml-1 {active === 'likes' ? '' : 'opacity-60'}">({likes.length})</span>
        </button>
      </div>
    </div>
  </header>
  <div class="wrap">
    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      {#if user.roles.includes('dev')}
        {#if active === 'themes'}
          {#each themes as addon}
            <Card {addon} />
          {/each}
        {:else if active === 'plugins'}
          {#each plugins as addon}
            <Card {addon} />
          {/each}
        {/if}
      {/if}
      {#if active === 'likes'}
        {#each likes as {addon}}
          <Card {addon} />
        {/each}
      {/if}
    </div>
  </div>
</Layout>