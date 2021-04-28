<script>
  import {Inertia} from '@inertiajs/inertia';
  import tooltip from '$lib/tooltip';
  import {InertiaLink, page} from '@inertiajs/inertia-svelte';
  import Icon, {DotsHorizontal, Trash, Pencil, ArrowUp, ArrowDown} from 'svelte-hero-icons';
  import dayjs from 'dayjs';
  import adFormat from 'dayjs/plugin/advancedFormat'

  dayjs.extend(adFormat);

  import CommentForm from './CommentForm.svelte';
  import Reply from './Reply.svelte';
  import Markdown from '../Markdown.svelte';
  import Dropdown from '../Dropdown.svelte';
  import AreYouSure from '../Modals/AreYouSure.svelte';

  export let comment;
  export let showReplies = true;
  let {auth} = $page.props;

  let repliesVisible = false;
  const toggleReplies = () => {
    repliesVisible = !repliesVisible
  }

  let replyFormVisible = false;
  let replyPostForm = {
    message: '',
    reply_id: comment.id,
    addon_id: comment.addon_id
  }

  let editForm = {
    message: comment.message,
    addon_id: comment.addon_id
  }
  let editComment = false;

  
  let deleteComment = false;
  let deleteSubmitting = false;
  const destroy = () => {
    Inertia.delete(`/comment/${comment.id}`, {
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

  const showReplyForm = () => {
    replyFormVisible = !replyFormVisible;
  }

  const pin = () => {
    Inertia.put(`/comment/pin/${comment.id}`, {}, {
      preserveScroll: true
    })
  }
</script>

<div class="relative mb-12 last:mb-0">

  <!-- Comment -->
  <div id="comment-{comment.id}" class="flex items-start">
    <InertiaLink href="/users/{comment.user.slug}" class="mr-4">
      <img src={comment.user.avatar} alt="User avatar" class="w-10 h-10 rounded-full">
    </InertiaLink>
    <div class="flex-1">
      <header class="flex items-center justify-between">
        <div class="flex items-center">
          <InertiaLink href="/users/{comment.user.slug}" class="font-display text-gray-800 dark:text-white">{comment.user.name}</InertiaLink>
          <time class="text-xs opacity-50 ml-2" datetime={comment.created_at}>{dayjs(comment.created_at).format('Do MMMM, YYYY - h:mm:sa')}</time>
          {#if comment.updated_at !== comment.created_at}
            <time class="text-xs opacity-50 ml-2 cursor-pointer" datatime={comment.updated_at} use:tooltip={{content: dayjs(comment.updated_at).format('Do MMMM, YYYY - h:mm:sa')}}>(edited)</time>
          {/if}
          {#if comment.pinned}
            <span class="bg-yellow-500 bg-opacity-10 text-yellow-700 dark:text-yellow-500 rounded ml-4 py-0.5 px-1.5 text-xs">Pinned</span>
          {/if}
        </div>
        {#if auth && (auth.id === comment.user_id || auth.roles.includes('admin') || (auth.roles.includes('dev') && auth.id === comment.addon.user_id))}
          <Dropdown btn="focus:outline-none p-0.5 rounded hover:bg-gray-50 dark:hover:bg-gray-800" menu="right-0">
            <Icon src={DotsHorizontal} class="w-5 h-5" />

            <svelte:fragment slot="menu">
              {#if auth.id === comment.user_id}
                <button class="menu-item" on:click={() => editComment = true}>
                  Edit <Icon src={Pencil} class="w-5 h-5" />
                </button>
              {/if}
              {#if auth.roles.includes('admin') || (auth.roles.includes('dev') && auth.id === comment.addon.user_id)}
                <button class="menu-item" on:click={pin}>
                  {#if comment.pinned}
                    Unpin <Icon src={ArrowDown} class="w-5 h-5" />
                  {:else}
                    Pin <Icon src={ArrowUp} class="w-5 h-5" />
                  {/if}
                </button>
                <hr class="border-gray-200 dark:border-gray-800 my-2 mx-1.5">
              {/if}
              {#if auth.id === comment.user_id || auth.roles.includes('admin') || (auth.roles.includes('dev') && auth.id === comment.addon.user_id)}
                <button class="menu-item menu-item-danger" on:click={() => deleteComment = true}>
                  Delete <Icon src={Trash} class="w-5 h-5" />
                </button>
              {/if}
            </svelte:fragment>
          </Dropdown>
        {/if}
      </header>
      {#if editComment}
        <CommentForm method="PUT" url="/comment/{comment.id}" form={editForm} isEditing={true} on:close={() => editComment = false} />
      {:else}
        <Markdown string={comment.message} />
      {/if}
    </div>
  </div>

  <!-- Replies -->
  {#if showReplies}
    <div class="ml-[56px] mt-2">
      <div class="flex mb-4">
        {#if comment.replies.length > 0}
          <button
            type="button"
            class="font-semibold mr-4 text-xs focus:outline-none"
            on:click={toggleReplies}
          >
            {#if repliesVisible}
              Hide replies
            {:else}
              Show replies
              <span class="dark:opacity-50">({comment.replies.length})</span>
            {/if}
          </button>
        {/if}
        {#if auth && !repliesVisible && !replyFormVisible}
          <button class="text-teal-600 dark:text-teal-500 font-semibold text-xs focus:outline-none" on:click={showReplyForm}>
            Reply
          </button>
        {/if}
      </div>
      {#if repliesVisible}
        <div>
          {#each comment.replies as reply}
            <Reply {reply} />
          {/each}
        </div>
        {#if !replyFormVisible}
          <button class="text-teal-600 dark:text-teal-500 font-semibold mb-2 ml-[48px] text-xs focus:outline-none" on:click={showReplyForm}>
            Reply
          </button>
        {/if}
      {/if}
      {#if replyFormVisible}
        <div class="mt-4">
          <CommentForm method="POST" url="/comment" form={replyPostForm} isReply={true} on:close={() => replyFormVisible = false} />
        </div>
      {/if}
    </div>
  {/if}
</div>

<AreYouSure title="Delete comment" submitting={deleteSubmitting} visible={deleteComment} on:close={() => deleteComment = false} on:confirm={destroy}>
  <p>Are you sure? This comment and any replies will be removed. This action <u>cannot</u> be undone.</p>
</AreYouSure>