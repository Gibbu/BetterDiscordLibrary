<script>
  import {Inertia} from '@inertiajs/inertia';
  import {InertiaLink, page} from '@inertiajs/inertia-svelte';
  import tooltip from '$lib/tooltip';
  import Icon, {DotsHorizontal, Trash, Pencil} from 'svelte-hero-icons';
  import dayjs from 'dayjs';
  import adFormat from 'dayjs/plugin/advancedFormat'

  dayjs.extend(adFormat);

  import CommentForm from './CommentForm.svelte';
  import Markdown from '../Markdown.svelte';
  import Dropdown from '../Dropdown.svelte';
  import AreYouSure from '../Modals/AreYouSure.svelte';

  export let reply;
  let {auth} = $page.props;

  let editForm = {
    message: reply.message,
    addon_id: reply.addon_id
  }
  let editComment = false;

  let deleteComment = false;
  let deleteSubmitting = false;
  const destroy = () => {
    Inertia.delete(`/comment/${reply.id}`, {
      preserveScroll: true,
      onStart() {
        deleteSubmitting = true;
      },
      onSuccess() {
        deleteSubmitting = false;
        deleteComment = false;
      }
    })
  }
</script>

<div id="comment-{reply.id}" class="flex items-start mt-4">
  <InertiaLink href="/users/{reply.user.slug}" class="mr-4">
    <img src={reply.user.avatar} alt="User avatar" class="w-8 h-8 rounded-full">
  </InertiaLink>
  <div class="flex-1">
    <header class="flex items-center justify-between">
      <div class="flex items-center">
        <InertiaLink href="/users/{reply.user.slug}" class="font-display text-gray-800 dark:text-white">{reply.user.name}</InertiaLink>
        <time class="text-xs opacity-50 ml-2" datetime={reply.created_at}>{dayjs(reply.created_at).format('Do MMMM, YYYY - h:mm:sa')}</time>
        {#if reply.updated_at !== reply.created_at}
          <time class="text-xs opacity-50 ml-2 cursor-pointer" datatime={reply.updated_at} use:tooltip={{content: dayjs(reply.updated_at).format('Do MMMM, YYYY - h:mm:sa')}}>(edited)</time>
        {/if}
        {#if reply.pinned}
          <span class="bg-yellow-500 bg-opacity-10 text-yellow-700 dark:text-yellow-500 rounded ml-4 py-0.5 px-1.5 text-xs">Pinned</span>
        {/if}
      </div>
      {#if auth && (auth.id === reply.user_id || auth.roles.includes('admin') || (auth.roles.includes('dev') && auth.id === reply.addon.user_id))}
        <Dropdown btn="focus:outline-none p-0.5 rounded hover:bg-gray-50 dark:hover:bg-gray-800" menu="right-0">
          <Icon src={DotsHorizontal} class="w-5 h-5" />

          <svelte:fragment slot="menu">
            {#if auth.id === reply.user_id}
              <button class="menu-item" on:click={() => editComment = true}>
                Edit <Icon src={Pencil} class="w-5 h-5" />
              </button>
            {/if}
            {#if auth.id === reply.user_id || auth.roles.includes('admin') || (auth.roles.includes('dev') && auth.id === reply.addon.user_id)}
              <button class="menu-item menu-item-danger" on:click={() => deleteComment = true}>
                Delete <Icon src={Trash} class="w-5 h-5" />
              </button>
            {/if}
          </svelte:fragment>
        </Dropdown>
      {/if}
    </header>
    {#if editComment}
      <CommentForm method="PUT" url="/comment/{reply.id}" form={editForm} isEditing={true} on:close={() => editComment = false} />
    {:else}
      <Markdown string={reply.message} />
    {/if}
  </div>
</div>

<AreYouSure title="Delete comment" visible={deleteComment} submitting={deleteSubmitting} on:close={() => deleteComment = false} on:confirm={destroy}>
  <p>Are you sure? This comment and any replies will be removed. This action <u>cannot</u> be undone.</p>
</AreYouSure>