<h1 align="center">MCQ Based Exam</h1>

## About

It is a web application using laravel inspire from Bangldesh Medical Exam. It has some features for online MCQ type exam such as

- Each question may have multiple answers.
- Exam Attempt Validation.
- Anti-marking policy like for incorrect answer mark = -0.25 . 
- Question Shuffle on each attempt.
- Exam Review on each attempt.
- Instant exam result shows after attempt.
- Real time exam question's answers store.

This is a demo project.

---

## Use case
![alt text](https://github.com/BimalNSU/mcq_based_exam_management/blob/master/diagrams/use_case.jpg?raw=true)
## ER diagram
![alt text](https://github.com/BimalNSU/mcq_based_exam_management/blob/master/diagrams/er_diagram.png?raw=true)
## Database physical diagram
![alt text](https://github.com/BimalNSU/mcq_based_exam_management/blob/master/diagrams/physical_diagram.png?raw=true)

---

## Challeges
The most challenging part in query is that calculate the exam total marks for exam review page.
Query code is below:

```SQL
SELECT e.exam_track_id,e.exam_id,e.attempt_no,e.student_id, e.student_start, SUM(r.marks) as total_marks
FROM (select q.exam_track_id, q.q_track_id,
            (case
                #if it is blank question,  return marks = 0	
                when 0 = 	(select COUNT(q1.is_selected) 
                            from exam_papers_q_options q1
                            where q.exam_track_id = q1.exam_track_id and
                                    q.q_track_id = q1.q_track_id and
                                    q1.is_selected = 1
                            ) then 0

                #if it is incorrect question,  return marks = -0.25
                when q.q_track_id in 	(select distinct q1.q_track_id
                                        from exam_papers_q_options q1
                                        where q.exam_track_id = q1.exam_track_id and
                                                q.q_track_id = q1.q_track_id and
                                                q1.q_track_id in 	(case
                                                                        when q1.is_selected=1 and
                                                                                q1.q_options not in (select a.q_options
                                                                                                    from exam_questions_details a
                                                                                                    where a.is_answers = 1 AND 
                                                                                                        q1.q_track_id = a.q_track_id
                                                                                                    ) 
                                                                                                    then q1.q_track_id
                                                                        when q1.is_selected = 0 and
                                                                                q1.q_options in (select a.q_options
                                                                                                from exam_questions_details a
                                                                                                where a.is_answers = 1 AND
                                                                                                    q1.q_track_id = a.q_track_id
                                                                                                ) 
                                                                                                then q1.q_track_id
                                                                        else null
                                                                    end
                                                                    ) 
                                        ) then -0.25
                #else question is correct and return marks = 1
                else 1
            end) as marks
    from exam_papers_q_options q
    WHERE q.exam_track_id = $exam_track_id
    GROUP BY q.q_track_id
    ) as r natural join exam_assign e ;
```                    
