<script>
  import {onMount} from 'svelte';
  import tooltip from '$lib/tooltip';
  import {InertiaLink, page} from '@inertiajs/inertia-svelte';
  import Icon, {ChevronDown, Logout, User, Plus, Cog, DotsHorizontal, QuestionMarkCircle, Newspaper, Users, Mail} from 'svelte-hero-icons';

  // State
  let {auth} = $page.props;

  // Components
  import Flash from './Flash.svelte';
  import Dropdown from '$components/Dropdown.svelte';
  import Form from '$components/Addons/Form.svelte';

  export let title = 'Home'
  export let width = 'max-w-screen-xl';

  const links = [
    {value: 'Themes', href: '/themes'},
    {value: 'Plugins', href: '/plugins'},
    {value: 'Devs', href: '/developers'}
  ]

  $: active = (href) => {
    const url = $page.url.split('/')[1].split('?')[0];
    if (url === href.replace('/', '')) return true;
    return false;
  }

  let formVisible = false;

  onMount(() => {
    document.querySelector('#app').removeAttribute('data-page');
  })
</script>

<svelte:head>
  <title>{title} - BetterDiscordLibrary</title>
</svelte:head>

<nav class="bg-gray-50 dark:bg-gray-800 shadow-lg fixed w-full top-0 flex h-20 items-center z-10">
  <div class="flex px-8 items-center justify-between w-full">
    <div class="flex items-center">
      <InertiaLink href="/">
        <img src="/images/logo.png" alt="Logo" class="w-10">
      </InertiaLink>
      <ul class="flex items-center">
        {#each links as {href, value}}
          <li class="ml-8">
            <InertiaLink {href} class="py-2 font-medium text-gray-700 dark:text-gray-200 focus:outline-none {active(href) ? 'text-teal-600 dark:text-teal-400' : 'hover:text-black dark:hover:text-white'}">
              {value}
            </InertiaLink>
          </li>
        {/each}
      </ul>
    </div>
    <div class="flex items-center">
      {#if auth}
        <Dropdown btn="flex items-center focus:outline-none" menu="right-0">
          <img src={auth.avatar} alt="User avatar" class="w-8 h-8 rounded-full">
          <span class="ml-2 mr-3 font-medium">{auth.name}</span>
          <Icon src={ChevronDown} class="h-3 w-3" />

          <svelte:fragment slot="menu">
            <InertiaLink href="/users/{auth.name}/{auth.discrim}" class="menu-item">
              My profile <Icon src={User} class="w-5 h-5" />
            </InertiaLink>
            <InertiaLink href="/settings" class="menu-item">
              Settings <Icon src={Cog} class="w-5 h-5" />
            </InertiaLink>
            {#if auth.roles.includes('dev')}
              <hr class="border-gray-200 dark:border-gray-600 my-2 mx-1.5">
              <button class="menu-item" on:click={() => formVisible = true}>
                Add addon <Icon src={Plus} class="w-5 h-5" />
              </button>
            {/if}
            {#if auth.roles.includes('admin')}
              <hr class="border-gray-200 dark:border-gray-600 my-2 mx-1.5">
              <InertiaLink href="/admin" class="menu-item">
                Admin <Icon src={Users} class="w-5 h-5" />
              </InertiaLink>
            {/if}
            <hr class="border-gray-200 dark:border-gray-600 my-2 mx-1.5">
            <a href="/logout" class="menu-item menu-item-danger">
              Logout <Icon src={Logout} class="w-5 h-5" />
            </a>
          </svelte:fragment>
        </Dropdown>
      {:else}
        <a href="/login" class="btn btn-primary bg-teal-500 hover:bg-teal-400 focus:bg-teal-400 focus:ring-teal-500 focus:ring-opacity-40">
          Login with Discord
        </a>
      {/if}
    </div>
  </div>
</nav>

<div id="page" class="w-full {width} mx-auto mt-20">
  <slot />
</div>

<Flash />

<footer id="footer" class="mt-24 flex items-center justify-between {width} w-full mx-auto py-4 text-gray-400 dark:text-gray-500">
  <p class="text-xs">BetterDiscordLibrary is <u>NOT</u> associated with BetterDiscord.</p>
  <a href="mailto:betterdiscordlibrary@gmail.com" use:tooltip={{content: 'Contact', delay: [250, 0]}}>
    <Icon src={Mail} class="w-4 h-4" />
  </a>
</footer>

{#if auth && auth.roles.includes('dev')}
  <Form visible={formVisible} on:close={() => formVisible = false} />
{/if}