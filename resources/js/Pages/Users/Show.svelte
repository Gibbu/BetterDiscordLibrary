<script>
  import Layout from '$layout/Layout.svelte';

  import Card from '$components/Addons/Card.svelte';

  // State
  export let user;
  export let themes;
  export let plugins;
  export let likes;

  $: active = (user.roles.includes('dev') ? 'themes' : 'likes');
  const setActive = page => {
    active = page;
  }
</script>

<Layout title="{user.name}#{user.discrim}">
  <div class="grid grid-cols-[250px,1fr] gap-4 items-start mt-12 mb-24">
    <aside class="bg-gray-50 dark:bg-gray-800 rounded overflow-hidden">
      <img src={user.avatar} alt="User avatar" class="w-full">
      <section class="p-4">
        <h1 class="text-gray-800 dark:text-white font-display text-xl truncate">{user.name}#{user.discrim}</h1>
        {#if user.links}
          {#if user.links.github}
            <a href="https://github.com/{user.links.github}" class="flex items-center mt-2 hover:text-teal-500">
              <svg class="w-4 h-4 mr-2" aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512"><path fill="currentColor" d="M165.9 397.4c0 2-2.3 3.6-5.2 3.6-3.3.3-5.6-1.3-5.6-3.6 0-2 2.3-3.6 5.2-3.6 3-.3 5.6 1.3 5.6 3.6zm-31.1-4.5c-.7 2 1.3 4.3 4.3 4.9 2.6 1 5.6 0 6.2-2s-1.3-4.3-4.3-5.2c-2.6-.7-5.5.3-6.2 2.3zm44.2-1.7c-2.9.7-4.9 2.6-4.6 4.9.3 2 2.9 3.3 5.9 2.6 2.9-.7 4.9-2.6 4.6-4.6-.3-1.9-3-3.2-5.9-2.9zM244.8 8C106.1 8 0 113.3 0 252c0 110.9 69.8 205.8 169.5 239.2 12.8 2.3 17.3-5.6 17.3-12.1 0-6.2-.3-40.4-.3-61.4 0 0-70 15-84.7-29.8 0 0-11.4-29.1-27.8-36.6 0 0-22.9-15.7 1.6-15.4 0 0 24.9 2 38.6 25.8 21.9 38.6 58.6 27.5 72.9 20.9 2.3-16 8.8-27.1 16-33.7-55.9-6.2-112.3-14.3-112.3-110.5 0-27.5 7.6-41.3 23.6-58.9-2.6-6.5-11.1-33.3 2.6-67.9 20.9-6.5 69 27 69 27 20-5.6 41.5-8.5 62.8-8.5s42.8 2.9 62.8 8.5c0 0 48.1-33.6 69-27 13.7 34.7 5.2 61.4 2.6 67.9 16 17.7 25.8 31.5 25.8 58.9 0 96.5-58.9 104.2-114.8 110.5 9.2 7.9 17 22.9 17 46.4 0 33.7-.3 75.4-.3 83.6 0 6.5 4.6 14.4 17.3 12.1C428.2 457.8 496 362.9 496 252 496 113.3 383.5 8 244.8 8zM97.2 352.9c-1.3 1-1 3.3.7 5.2 1.6 1.6 3.9 2.3 5.2 1 1.3-1 1-3.3-.7-5.2-1.6-1.6-3.9-2.3-5.2-1zm-10.8-8.1c-.7 1.3.3 2.9 2.3 3.9 1.6 1 3.6.7 4.3-.7.7-1.3-.3-2.9-2.3-3.9-2-.6-3.6-.3-4.3.7zm32.4 35.6c-1.6 1.3-1 4.3 1.3 6.2 2.3 2.3 5.2 2.6 6.5 1 1.3-1.3.7-4.3-1.3-6.2-2.2-2.3-5.2-2.6-6.5-1zm-11.4-14.7c-1.6 1-1.6 3.6 0 5.9 1.6 2.3 4.3 3.3 5.6 2.3 1.6-1.3 1.6-3.9 0-6.2-1.4-2.3-4-3.3-5.6-2z"></path></svg>
              GitHub Profile
            </a>
          {/if}
          {#if user.links.paypal}
            <a href={user.links.paypal} class="flex items-center mt-2 hover:text-teal-500">
              <svg class="w-4 h-4 mr-2" aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path fill="currentColor" d="M111.4 295.9c-3.5 19.2-17.4 108.7-21.5 134-.3 1.8-1 2.5-3 2.5H12.3c-7.6 0-13.1-6.6-12.1-13.9L58.8 46.6c1.5-9.6 10.1-16.9 20-16.9 152.3 0 165.1-3.7 204 11.4 60.1 23.3 65.6 79.5 44 140.3-21.5 62.6-72.5 89.5-140.1 90.3-43.4.7-69.5-7-75.3 24.2zM357.1 152c-1.8-1.3-2.5-1.8-3 1.3-2 11.4-5.1 22.5-8.8 33.6-39.9 113.8-150.5 103.9-204.5 103.9-6.1 0-10.1 3.3-10.9 9.4-22.6 140.4-27.1 169.7-27.1 169.7-1 7.1 3.5 12.9 10.6 12.9h63.5c8.6 0 15.7-6.3 17.4-14.9.7-5.4-1.1 6.1 14.4-91.3 4.6-22 14.3-19.7 29.3-19.7 71 0 126.4-28.8 142.9-112.3 6.5-34.8 4.6-71.4-23.8-92.6z"></path></svg>
              PayPal
            </a>
          {/if}
          {#if user.links.patreon}
            <a href={user.links.patreon} class="flex items-center mt-2 hover:text-teal-500">
              <svg class="w-4 h-4 mr-2" aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M512 194.8c0 101.3-82.4 183.8-183.8 183.8-101.7 0-184.4-82.4-184.4-183.8 0-101.6 82.7-184.3 184.4-184.3C429.6 10.5 512 93.2 512 194.8zM0 501.5h90v-491H0v491z"></path></svg>
              Patreon Page
            </a>
          {/if}
        {/if}
      </section>
    </aside>
    <main>
      <header class="border-gray-300 dark:border-gray-800 border-b flex items-center">
        {#if user.roles.includes('dev')}
          <button class="flex items-center pb-4 mr-6 border-b relative top-[1px] focus:outline-none {active === 'themes' ? 'border-teal-500' : 'border-transparent'}" on:click={() => setActive('themes')}>
            Themes <span class="text-sm text-gray-500 dark:text-gray-400 ml-1">({themes.length})</span>
          </button>
          <button class="flex items-center pb-4 mr-6 border-b relative top-[1px] focus:outline-none {active === 'plugins' ? 'border-teal-500' : 'border-transparent'}" on:click={() => setActive('plugins')}>
            Plugins <span class="text-sm text-gray-500 dark:text-gray-400 ml-1">({plugins.length})</span>
          </button>
        {/if}
        <button class="flex items-center pb-4 border-b relative top-[1px] focus:outline-none {active === 'likes' ? 'border-teal-500' : 'border-transparent'}" on:click={() => setActive('likes')}>
          Likes <span class="text-sm text-gray-500 dark:text-gray-400 ml-1">({likes.length})</span>
        </button>
      </header>
      <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
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
    </main>
  </div>
</Layout>