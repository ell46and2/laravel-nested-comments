<?php

namespace App\Traits\Eloquent;

trait NestableCommentsTrait
{
	public function nestedComments($page = 1, $perPage = 10)
	{
		$comments = $this->comments();

		// get paginated comment ids, so we only need to eager load for those comments
		$ids = $this->getCommentIds($comments, $page, $perPage);

		return $this->getComments($comments, $ids, $page, $perPage);
	}

	protected function getCommentIds($comments, $page, $perPage)
	{
		$grouped = $comments->get()->groupBy('parent_id');

		$root = $grouped->get(null)->forPage($page, $perPage); // top level comments

		return $this->buildIdNest($root, $grouped);
	}

	protected function getComments($comments, $ids, $page, $perPage)
	{
		$grouped = $comments->whereIn('id', $ids)->with(['user', 'parent.user'])->get()->groupBy('parent_id');

		$root = $grouped->get(null); // top level comments

		return $this->buildNest($root, $grouped);
	}

	protected function buildIdNest($root, $grouped, &$ids = [])
	{
		foreach ($root as $comment) {
			$ids[] = $comment->id;

			if($replies = $grouped->get($comment->id)) {
				$this->buildIdNest($replies, $grouped, $ids);
			}
		}

		return $ids;
	}

	// Starts off with the top level comments, and will add any direct children to a children property, it will then recursively call the sam emethod on the children until it has got all the nested replies.
	protected function buildNest($comments, $groupedComments)
	{
		return $comments->each(function($comment) use ($groupedComments) {
			if($replies = $groupedComments->get($comment->id)) {
				$comment->children = $replies;
				$this->buildNest($comment->children, $groupedComments);
			}
		});
	}
}