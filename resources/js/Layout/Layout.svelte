<script>
  import {onMount} from 'svelte';
  import {InertiaLink, page} from '@inertiajs/inertia-svelte';
  import Icon, {ChevronDown, Logout, User, Plus, Cog, DotsHorizontal, QuestionMarkCircle, Newspaper, Users} from 'svelte-hero-icons';

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

  const form = {
    id: null,
    type: 'theme',
    name: 'Slate',
    description: `An optimized, consistent, and functional theme for Discord based on GitHub's design language.`,
    download: 'https://betterdiscord.net/ghdl?id=3358',
    thumbnail: '',
    contributors: ['Tropix126'],
    images: ['https://i.imgur.com/GEcQZhN.png'],
    release: '2020-08-06',
    source: 'https://github.com/DiscordStyles/Slate'
  }

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
        <Dropdown btn="w-8 h-8 flex rounded hover:bg-gray-200 dark:hover:bg-gray-700 focus:outline-none" menu="left-0" container="ml-8">
          <Icon src={DotsHorizontal} class="w-4 h-4 m-auto" />

          <div slot="menu">
            <InertiaLink href="/about" class="menu-item">
              About <Icon src={QuestionMarkCircle} class="w-5 h-5" />
            </InertiaLink>
            <InertiaLink href="/about" class="menu-item">
              Changelog <Icon src={Newspaper} class="w-5 h-5" />
            </InertiaLink>
          </div>
        </Dropdown>
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

<footer id="footer" class="py-4">
  <div class="{width} mx-auto">
    <p>Wow</p>
  </div>
</footer>

{#if auth && auth.roles.includes('dev')}
  <Form data={form} visible={formVisible} on:close={() => formVisible = false} />
{/if}