<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Repositories\CommentRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;
use Response;

class CommentController extends AppBaseController
{
    /** @var  CommentRepository */
    private $commentRepository;

    public function __construct(CommentRepository $commentRepo)
    {
        $this->commentRepository = $commentRepo;
    }

    /**
     * Display a listing of the Comment.
     *
     * @param Request $request
     *
     * @return Response|Factory|RedirectResponse|Redirector|View
     */
    public function index(Request $request)
    {
        $comments = $this->commentRepository->all();

        return view('comments.index')
            ->with('comments', $comments);
    }

    /**
     * Show the form for creating a new Comment.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|Response
     */
    public function create()
    {
        return view('comments.create');
    }

    /**
     * Store a newly created Comment in storage.
     *
     * @param CreateCommentRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|Response
     */
    public function store(CreateCommentRequest $request)
    {
        $input = $request->all();

        $comment = $this->commentRepository->create($input);

        Flash::success('Comment saved successfully.');

        return redirect(route('comments.index'));
    }

    /**
     * Display the specified Comment.
     *
     * @param int $id
     *
     * @return Factory|RedirectResponse|Redirector|View|Response
     */
    public function show($id)
    {
        $comment = $this->commentRepository->find($id);

        if (empty($comment)) {
            Flash::error('Comment not found');

            return redirect(route('comments.index'));
        }

        return view('comments.show')->with('comment', $comment);
    }

    /**
     * Show the form for editing the specified Comment.
     *
     * @param int $id
     *
     * @return Factory|RedirectResponse|Redirector|View|Response
     */
    public function edit($id)
    {
        $comment = $this->commentRepository->find($id);

        if (empty($comment)) {
            Flash::error('Comment not found');

            return redirect(route('comments.index'));
        }

        return view('comments.edit')->with('comment', $comment);
    }

    /**
     * Update the specified Comment in storage.
     *
     * @param int $id
     * @param UpdateCommentRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|Response
     */
    public function update($id, UpdateCommentRequest $request)
    {
        $comment = $this->commentRepository->find($id);

        if (empty($comment)) {
            Flash::error('Comment not found');

            return redirect(route('comments.index'));
        }

        $comment = $this->commentRepository->update($request->all(), $id);

        Flash::success('Comment updated successfully.');

        return redirect(route('comments.index'));
    }

    /**
     * Remove the specified Comment from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|Response
     */
    public function destroy($id)
    {
        $comment = $this->commentRepository->find($id);

        if (empty($comment)) {
            Flash::error('Comment not found');

            return redirect(route('comments.index'));
        }

        $this->commentRepository->delete($id);

        Flash::success('Comment deleted successfully.');

        return redirect(route('comments.index'));
    }
}