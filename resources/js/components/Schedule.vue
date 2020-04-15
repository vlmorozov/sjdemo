<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Schedule</div>

                    <div class="card-body">
                        <div>
                            <button v-on:click="save">Save</button>
                        </div>
                        <div>
                            <label>Дата начала</label>
                            <datepicker
                                v-model="dateFrom"
                                name="dateFrom"
                                :useUtc="false"
                            />
                        </div>
                        <div>
                            <label>Дата To</label>
                            <datepicker
                                v-model="dateTo"
                                name="dateTo"
                                :useUtc="false"
                            />
                        </div>
                        <div>
                            <label>SchoolClass</label>
                            <select v-model="schoolClassId">
                                <option
                                    v-for="sClass in dicts.schoolClasses"
                                    :value="sClass.id"
                                >{{sClass.name}}</option>
                            </select>
                        </div>
                        <div>
                            <table>
                                <tr>
                                    <th>Subject</th>
                                    <th>Teacher</th>
                                    <th>Cabinet</th>
                                </tr>
                                <tr v-for="(subject, ind) in subjectsForSchedule">
                                    <td>{{subjectsForSchedule[ind].subject.title}}</td>
                                    <td>{{subjectsForSchedule[ind].teacher.name}}</td>
                                    <td>{{subjectsForSchedule[ind].cabinet.title}}</td>
                                </tr>
                                <tr>
                                    <td>
                                        <select v-model="newSubjectsForSchedule.subject">
                                            <option
                                                v-for="subject in dicts.subjects"
                                                :value="subject.id"
                                            >{{subject.title}}</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select v-model="newSubjectsForSchedule.teacher">
                                            <option
                                                v-for="teacher in dicts.teachers"
                                                :value="teacher.id"
                                            >{{teacher.name}}</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select v-model="newSubjectsForSchedule.cabinet">
                                            <option
                                                v-for="cabinet in dicts.cabinets"
                                                :value="cabinet.id"
                                            >{{cabinet.number}}-{{cabinet.title}}</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <button v-on:click="addSubject">Add</button>
                                    </td>
                                </tr>
                            </table>
                        </div>

                    </div>
                </div>

                <div class="row">
                    <div
                        class="col-sm-1"
                        v-for="(wd) in [1,2,3,4,5,6,0]"
                    >
                        <div class="weekday">{{ dicts.weekdayNames[wd] }}</div>
                        <template v-for="(lesson, index) in scheduleWeek[wd]">
                        <div class="lesson lesson-"
                        ><select
                            v-model="scheduleWeek[wd][index].subjectId"
                            v-on:change="changeSubjectOnWeek(wd, index)"
                        >
                            <option v-for="(subject, ind) in subjectsForSchedule"
                            :value="ind">{{subject.subject.title}}-{{subject.teacher.name}}-{{subject.cabinet.number}}</option>
                        </select></div>
                        </template>
                    </div>
                </div>

                <div class="row">
                    <template v-for="(scheduleDay, date) in schedule">
                        <div class="col-md-1">
                            <div class="date">{{ date }}</div>
                            <template v-for="(lesson, index) in scheduleDay">
                            <div class="lesson lesson-"
                            ><select v-model="schedule[date][index].subjectId">
                                <option v-for="(subject, ind) in subjectsForSchedule"
                                :value="ind">{{subject.subject.title}}-{{subject.teacher.name}}-{{subject.cabinet.number}}</option>
                            </select></div>
                            </template>
                        </div>
                        <template v-if="(new Date(date)).getDay() == 0">
                            <div class="col-md-5"></div>
                        </template>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Datepicker from 'vuejs-datepicker';
    import axios from 'axios';

    export default {
        props: [
            'data',
            'subjects',
            'teachers',
            'cabinets',
            'schoolclasses'
        ],
        components: {
            Datepicker
        },
        watch: {
           dateFrom: function () {
               this.rebuildSchedule();
               this.fillFromWeekSchedule();
           },
            dateTo: function () {
                this.rebuildSchedule();
                this.fillFromWeekSchedule();
            },
        },
        methods: {
            formatDate: function(date) {
                return [date.getFullYear(), (date.getMonth()+1 < 10 ? '0'+ (date.getMonth()+1): date.getMonth()+1), date.getDate()<10 ? '0'+date.getDate() : date.getDate()].join('-')
            },

            save: function() {
                let saveData = {};

                saveData.scheduleId = this.scheduleId;
                saveData.classId = this.schoolClassId;
                saveData.dateFrom = this.dateFrom;
                saveData.dateTo = this.dateTo;

                saveData.subjectsForSchedule = [];
                for(let subjectId in this.subjectsForSchedule ) {
                    if (this.subjectsForSchedule.hasOwnProperty(subjectId)) {
                        saveData.subjectsForSchedule.push(subjectId);
                    }
                }

                saveData.scheduleWeekday = [];
                for(let wd=0; wd<=6; wd++) {
                    saveData.scheduleWeekday[wd] = this.scheduleWeek[wd];
                }

                saveData.schedule = [];
                for(let date in this.schedule ) {
                    if (this.schedule.hasOwnProperty(date)) {
                        let day = this.schedule[date];
                            day.map((lesson, numLesson) => {
                                if (lesson.active) {
                                    //console.log(lesson);

                                    saveData.schedule.push({
                                        date: lesson.date,
                                        lesson: numLesson,
                                        subjectId: lesson.subjectId !== null ? this.subjectsForSchedule[lesson.subjectId].subject.id : null,
                                        teacherId: lesson.subjectId !== null ? this.subjectsForSchedule[lesson.subjectId].teacher.id : null,
                                        cabinetId: lesson.subjectId !== null ? this.subjectsForSchedule[lesson.subjectId].cabinet.id : null,
                                    });
                                }
                            });
                    }
                }
                console.log(saveData);

                axios.post('/schedule', saveData)
                    .then(function (response) {
                        console.log(response);
                    });
            },

            _clone: function(data) {
                let text = JSON.stringify(data);
                return JSON.parse(text);
            },
            fillFromWeekSchedule: function() {
                this.scheduleWeek.map((day, wd) => {
                    day.map((el, lesson) => {
                        this.changeSubjectOnWeek(wd, lesson);
                    })
                });
            },
            changeSubjectOnWeek: function(wd, lesson) {
                let curDate = new Date(this.dateFrom);
                let endDate = new Date(this.dateTo);

                while (curDate <= endDate) {
                    if (curDate.getDay() == wd) {
                        let sDate = this.formatDate(curDate);
                        this.schedule[sDate][lesson].subjectId = this.scheduleWeek[wd][lesson].subjectId;
                    }
                    curDate.setDate(curDate.getDate() + 1);
                }
            },
            rebuildSchedule: function() {
                this.schedule = {};

                let startDate = new Date(this.dateFrom);
                startDate.setDate(startDate.getDate() - (startDate.getDay()?startDate.getDay():7) + 1);
                let finishDate = new Date(this.dateTo);
                finishDate.setDate(finishDate.getDate() + (7 - (finishDate.getDay()?finishDate.getDay():7)));

                let curDate = new Date(startDate);

                while (curDate <= finishDate) {
                    let sDate = this.formatDate(curDate);
                    this.schedule[sDate] = [];
                    for (let l=0; l < this.cntLessonsPerDay; l++) {
                        this.schedule[sDate][l] = {
                            active: (this.dateFrom <= curDate && curDate <= this.dateTo),
                            date: sDate,
                            numOfLesson: l+1,
                            subjectId: null,
                        };
                    }

                    curDate.setDate(curDate.getDate() + 1);
                }
                this.$forceUpdate();
            },

            addSubject: function() {
                let subject = {};
                this.dicts.subjects.map((el) => {
                   if (el.id == this.newSubjectsForSchedule.subject) {
                       subject.subject = el;
                   }
                });
                this.dicts.teachers.map((el) => {
                   if (el.id == this.newSubjectsForSchedule.teacher) {
                       subject.teacher = el;
                   }
                });
                this.dicts.cabinets.map((el)  => {
                   if (el.id == this.newSubjectsForSchedule.cabinet) {
                       subject.cabinet = el;
                   }
                });
                // this.subjectsForSchedule.push(subject);
                this.subjectsForSchedule[[subject.subject.id, subject.teacher.id, subject.cabinet.id].join('-')] = subject;

                this.$forceUpdate();
            },

        },
        mounted() {
            console.log('Schedule.')
         // this.rebuildSchedule();
        },
        data: function () {
            console.log(this.data);

            let data = {
                loading: false,
                dicts: {
                    weekdayNames: {
                        0: 'Вс',
                        1: 'Пн',
                        2: 'Вт',
                        3: 'Ср',
                        4: 'Чт',
                        5: 'Пт',
                        6: 'Сб',
                    },
                    subjects: this.subjects,
                    teachers: this.teachers,
                    cabinets: this.cabinets,
                    schoolClasses: this.schoolclasses

                },
                cntLessonsPerDay: 10,
                scheduleId: this.data.id ? this.data.id : null,
                dateFrom: this.data.dateFrom ? Date.parse(this.data.dateFrom) : new Date(),
                dateTo: this.data.dateTo ? Date.parse(this.data.dateTo) : new Date(),
                schoolClassId: this.data.class_id ? this.data.class_id : null ,
                subjectsForSchedule: {},
                newSubjectsForSchedule: {
                    subject: null,
                    teacher: null,
                    cabinet: null
                },
                schedule: {},
                scheduleWeek: [],
            };

            for(let wd=0; wd <7; wd++) {
                data.scheduleWeek[wd] = [];
                for (let l=0; l < data.cntLessonsPerDay; l++) {
                    data.scheduleWeek[wd][l] = {
                        weekday: wd,
                        numOfLesson: l+1,
                        subjectId: null,
                    };
                }
            }

            data.schedule = {};

            let startDate = new Date(data.dateFrom);
            startDate.setDate(startDate.getDate() - (startDate.getDay()?startDate.getDay():7) + 1);
            let finishDate = new Date(data.dateTo);
            finishDate.setDate(finishDate.getDate() + (7 - (finishDate.getDay()?finishDate.getDay():7)));

            let curDate = new Date(startDate);

            while (curDate <= finishDate) {
                let sDate = this.formatDate(curDate);
                data.schedule[sDate] = [];
                for (let l=0; l < data.cntLessonsPerDay; l++) {
                    data.schedule[sDate][l] = {
                        active: (data.dateFrom <= curDate && curDate <= data.dateTo),
                        date: sDate,
                        numOfLesson: l+1,
                        subjectId: null,
                    };
                }

                curDate.setDate(curDate.getDate() + 1);
            }

            if (this.data.schedule_subjects) {
                this.data.schedule_subjects.map((scheduleSubject) => {
                    let subject = null;
                    data.dicts.subjects.map((el) => {
                        if (el.id == scheduleSubject.subject_id) {
                            subject = el;
                        }
                    });
                    let teacher = null;
                    data.dicts.teachers.map((el) => {
                        if (el.id == scheduleSubject.teacher_id) {
                            teacher = el;
                        }
                    });
                    let cabinet = null;
                    data.dicts.cabinets.map((el) => {
                        if (el.id == scheduleSubject.cabinet_id) {
                            cabinet = el;
                        }
                    });

                    data.subjectsForSchedule[[scheduleSubject.subject_id, scheduleSubject.teacher_id, scheduleSubject.cabinet_id].join('-')] = {
                        subject: subject,
                        teacher: teacher,
                        cabinet: cabinet
                    };
                });

            }

            if (this.data.schedule_week) {
                this.data.schedule_week.map((scheduleWeekday) => {
                    let wd = scheduleWeekday.weekday,
                        l = scheduleWeekday.lesson-1,
                        subjectId = [scheduleWeekday.subject_id, scheduleWeekday.teacher_id, scheduleWeekday.cabinet_id].join('-');

                    data.scheduleWeek[wd][l] = {
                        weekday: wd,
                        numOfLesson: l+1,
                        subjectId: subjectId,
                    };
                });
            }

            if (this.data.schedule_lessons) {
                this.data.schedule_lessons.map((scheduledLesson) => {
                    let sDate = scheduledLesson.date,
                        l = scheduledLesson.lesson_number,
                        subjectId = [scheduledLesson.subject_id, scheduledLesson.teacher_id, scheduledLesson.cabinet_id].join('-');

                    console.log(data.schedule[sDate]);

                    data.schedule[sDate][l]['date'] = sDate;
                    data.schedule[sDate][l]['numOfLesson'] = l+1;
                    data.schedule[sDate][l]['subjectId'] = subjectId;
                });
            }

            return data;
        }
    }
</script>
