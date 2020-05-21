<?php

namespace App\Http\Controllers;

use App\CourseBatch;
use App\CourseBatchAuthor;
use App\Filters\CourseBatchCollectionFilters;
use App\Http\Requests\CreateCourseBatchRequest;
use App\Http\Requests\ListCourseBatchRequest;
use App\Http\Requests\UpdateCourseBatchRequest;
use App\Http\Resources\CourseBatch as CourseBatchResource;
use App\Http\Resources\CourseBatchCollection;
use Illuminate\Http\Request;

class CourseBatchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ListCourseBatchRequest $request, CourseBatchCollectionFilters $filters)
    {
        // $authorId = (int) $request->authorId;
        // $courseId = (int) $request->courseId;
        // $q = $request->q ?? '';
        // $builder = CourseBatch::where('course_batches.course_id', $courseId)
        //     ->where(function ($query) use ($q) {
        //         if ($q) {
        //             return $query->where('batch_name', 'like', '%' . $q . '%');
        //         }
        //         return $query;
        //     });

        // if ($authorId) {
        //     $assignedBatchIds = CourseBatchAuthor::whereCourseId($courseId)
        //     ->whereAuthorId($authorId)
        //     ->get()
        //     ->map(function ($batch) {
        //         return $batch->course_batch_id;
        //     })
        //     ->filter(function ($courseBatchId) {
        //         return $courseBatchId;
        //     })
        //     ->toArray();

        //     $builder = $builder
        //         ->join('course_batch_author', 'course_batches.id', '=', 'course_batch_author.course_batch_id')
        //         ->where(function ($q) use ($courseId, $authorId, $assignedBatchIds) {
        //             if (count($assignedBatchIds) > 0 ) {
        //                 // $q = $q->where('course_batch_instructor.course_batch_id', '!=', $courseBatchInstructor->course_batch_id);
        //                 $q = $q->whereNotIn('course_batch_author.course_batch_id', $assignedBatchIds);
        //             }
        //             return $q
        //                 ->where('course_batch_author.course_id', $courseId)
        //                 ->where('course_batch_author.author_id', '!=', $authorId);
        //         })
        //         ->groupBy('course_batch_author.course_batch_id', 'course_batches.batch_name', 'course_batches.id')
        //         ->select('course_batch_author.course_batch_id', 'course_batches.batch_name', 'course_batches.id');
        // }

        // // $courseBatches = $builder->get()->unique('instructor_id')->values();
        // $courseBatches = $builder->get();
        // // dd($courseBatches);
        // return response()->json(new CourseBatchCollection($courseBatches));
        // // return new CourseBatchCollection($courseBatches);

        return response()->json(
            new CourseBatchCollection(
                CourseBatch::filterUsing($filters)
                    ->get()
                    ->unique('instructor_id')
                    ->values()
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCourseBatchRequest $request)
    {
        // $request->request->add(['price' => 'value']);
        $courseBatch = CourseBatch::store($request);

        $payload = [
            'author_id' => auth()->user()->id,
            'course_id' => $request->courseId,
            'course_batch_id' => $courseBatch->id,
            'timetable' => $request->timetable,
            'price' => $request->price,
        ];
        // first check if a space exists

        $courseBatchAuthor = CourseBatchAuthor::whereAuthorId(auth()->user()->id)
            ->whereCourseId($request->courseId)
            ->whereCourseBatchId(null)->first();

        if ($courseBatchAuthor) {
            $courseBatchAuthor->update($payload);
        } else {
            CourseBatchAuthor::create($payload);
        }


        $courseBatch = CourseBatch::where('course_batches.id', $courseBatch->id)
            ->join(
                'course_batch_author',
                'course_batch_author.course_batch_id',
                'course_batches.id'
            )
            ->select(
                'course_batches.*',
                'course_batch_author.timetable'
            )
            ->first();

        // dd($courseBatch->toArray());

        return response()->json([
            'data' => new CourseBatchResource($courseBatch),
            'message' => 'Successfully fetched course batch',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CourseBatch  $courseBatch
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCourseBatchRequest $request, CourseBatch $courseBatch)
    {
        //ensure that only someone with the right permission can erform this action
        $courseBatch->update([
            'batch_name' => $request->batchName ?? $courseBatch->batch_name,
            'start_date' => $request->commencementDate ?? $courseBatch->start_date,
            'mode_of_delivery' => $request->modeOfDelivery ?? $courseBatch->mode_of_delivery,
            'course_id' => $request->courseId ?? $courseBatch->course_id,
            'end_date' => $request->endDate ?? $courseBatch->end_date,
            'has_ended' => $request->hasEnded ?? $courseBatch->has_ended,
            'price' => $request->price ?? $courseBatch->price,
            // 'timetable' => $request->timetable ?? $courseBatch->timetable,
            // 'instructor_id' => $request->user()->userable->id,
        ]);

        $authorId = auth()->user()->id;

        $courseBatchAuthor = CourseBatchAuthor::whereAuthorId($authorId)
            ->whereCourseBatchId($courseBatch->id)->first();

        if ($courseBatchAuthor) {
            $courseBatchAuthor->update([
                'timetable' => $request->timetable
            ]);
        }

        $courseBatch = CourseBatch::where('course_batches.id', $courseBatch->id)
            ->join('course_batch_author', 'course_batch_author.course_batch_id', 'course_batches.id')
            ->where('course_batch_author.course_batch_id', $courseBatch->id)
            ->where('course_batch_author.author_id', $authorId)
            ->select('course_batches.*', 'course_batch_author.timetable')
            ->first();

        return response()->json([
            'data' => new CourseBatchResource($courseBatch),
            'message' => 'Successfully updated course batch',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CourseBatch  $courseBatch
     * @return \Illuminate\Http\Response
     */
    public function destroy(CourseBatch $courseBatch)
    {
        // dd($courseBatch);
        $courseBatch->forceDelete();
        return response()->json([
            'message' => 'Succesfully deleted',
        ]);
    }
}
