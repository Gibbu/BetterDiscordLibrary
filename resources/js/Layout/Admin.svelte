<script>
  import {InertiaLink, page} from '@inertiajs/inertia-svelte';
  import Icon, {Users, Code, Speakerphone} from 'svelte-hero-icons';

  import Layout from './Layout.svelte';

  export let title;

  const icons = {Users, Code, Speakerphone}
  const nav = [
    {href: 'users', value: 'Users', icon: 'Users'},
    {href: 'addons', value: 'Addons', icon: 'Code'},
    {href: 'announcement', value: 'Announcement', icon: 'Speakerphone'},
  ]

  $: active = (href) => {
    const url = $page.url.split('/')[2].split('?')[0];
    if (url === href) return true;
    return false;
  }
</script>

<Layout {title}>

  <div class="wrap">
    <div class="mt-12 grid grid-cols-[250px,1fr] gap-4 items-start">
      <aside class="sticky top-4">
        {#each nav as {href, value, icon}}
          <InertiaLink href="/admin/{href}" class="py-3 px-4 rounded flex items-center {active(href) ? 'bg-teal-500 bg-opacity-10 text-teal-700 dark:text-teal-500' : ''}">
            <Icon src={icons[icon]} class="w-5 h-5 mr-2" /> {value}
          </InertiaLink>
        {/each}
      </aside>
      <main>
        <slot />
      </main>
    </div>
  </div>

</Layout>