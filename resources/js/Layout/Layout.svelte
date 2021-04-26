<script>
  import {onMount} from 'svelte';
  import tooltip from '$lib/tooltip';
  import {InertiaLink, page} from '@inertiajs/inertia-svelte';
  import Icon, {
    ChevronDown,
    Logout,
    User,
    Plus,
    Cog,
    DotsHorizontal,
    ColorSwatch,
    Puzzle,
    QuestionMarkCircle,
    AcademicCap,
    Code,
    Users,
    Mail
  } from 'svelte-hero-icons';

  // State
  let {auth} = $page.props;

  // Components
  import Flash from './Flash.svelte';
  import Dropdown from '$components/Dropdown.svelte';
  import Form from '$components/Addons/Form.svelte';

  export let title = 'Home'

  const icons = {ColorSwatch, Puzzle, QuestionMarkCircle, AcademicCap, Code}
  const nav = [
    {
      title: 'Menu',
      links: [
        {el: 'inertia', value: 'Themes', href: '/themes', icon: 'ColorSwatch'},
        {el: 'inertia', value: 'Plugins', href: '/plugins', icon: 'Puzzle'},
        {el: 'inertia', value: 'Devs', href: '/developers', icon: 'Code'}
      ]
    },
    {
      title: 'Resources',
      links: [
        {el: 'a', value: 'Guide', href: 'http://bd.zerebos.com', icon: 'AcademicCap'},
        {el: 'inertia', value: 'About / FAQs', href: '/about', icon: 'QuestionMarkCircle'}
      ]
    }
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

<nav class="fixed top-0 left-0 h-full w-[300px] border-r border-gray-300 dark:border-gray-800 p-10">
  <h1 class="mb-10 font-display text-gray-800 dark:text-white text-3xl font-bold">
    <InertiaLink href="/">BDLibrary</InertiaLink>
  </h1>
  {#each nav as {title, links}}
    <h5 class="mb-6 text-xs font-display font-bold uppercase text-gray-500 dark:text-gray-400">{title}</h5>
    <ul class="mb-6 pb-6 border-b border-gray-300 dark:border-gray-800 last:border-0 last:pb-0">
      {#each links as {el, href, value, icon}}
        <li class="mb-8 last:mb-0">
          {#if el === 'inertia'}
            <InertiaLink {href} class="flex items-center w-full {active(href) ? 'text-teal-600 dark:text-teal-500' : 'hover:text-black dark:hover:text-white'}">
              <Icon src={icons[icon]} class="w-6 h-6 mr-4" /> {value}
            </InertiaLink>
          {:else if el === 'a'}
            <a {href} target="_blank" class="flex items-center w-full hover:text-black dark:hover:text-white">
              <Icon src={icons[icon]} class="w-6 h-6 mr-4" /> {value}
            </a>
          {/if}
        </li>
      {/each}
    </ul>
  {/each}
</nav>

<div id="page" class="ml-[300px]">
  <header class="bg-gray-200 dark:bg-gray-900 sticky top-0 z-10 flex items-center min-h-[90px] max-h-[90px] border-b border-gray-300 dark:border-gray-800">
    <div class="wrap flex item-center justify-between">
      <span></span>
      {#if auth}
        <Dropdown btn="flex items-center focus:outline-none" menu="right-0">
          <img src={auth.avatar} alt="User avatar" class="w-8 h-8 rounded-full">
          <span class="ml-2 mr-3 font-medium">{auth.name}</span>
          <Icon src={ChevronDown} class="h-3 w-3" />

          <svelte:fragment slot="menu">
            <InertiaLink href="/users/{auth.slug}" class="menu-item">
              My profile <Icon src={User} class="w-5 h-5" />
            </InertiaLink>
            <InertiaLink href="/settings" class="menu-item">
              Settings <Icon src={Cog} class="w-5 h-5" />
            </InertiaLink>
            {#if auth.roles.includes('dev')}
              <hr class="border-gray-200 dark:border-gray-800 my-2 mx-1.5">
              <button class="menu-item" on:click={() => formVisible = true}>
                Add addon <Icon src={Plus} class="w-5 h-5" />
              </button>
            {/if}
            {#if auth.roles.includes('admin')}
              <hr class="border-gray-200 dark:border-gray-800 my-2 mx-1.5">
              <InertiaLink href="/admin" class="menu-item">
                Admin <Icon src={Users} class="w-5 h-5" />
              </InertiaLink>
            {/if}
            <hr class="border-gray-200 dark:border-gray-800 my-2 mx-1.5">
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
  </header>
  <slot />
</div>

<Flash />

<footer id="footer" class="mt-24 ml-[300px] flex py-4 text-gray-400 dark:text-gray-500">
  <div class="wrap flex items-center justify-between">
    <p class="text-xs">BetterDiscordLibrary is <u>NOT</u> associated with BetterDiscord.</p>
    <a href="mailto:betterdiscordlibrary@gmail.com" use:tooltip={{content: 'Contact', delay: [250, 0]}}>
      <Icon src={Mail} class="w-4 h-4" />
    </a>
  </div>
</footer>

{#if auth && auth.roles.includes('dev')}
  <Form visible={formVisible} on:close={() => formVisible = false} />
{/if}