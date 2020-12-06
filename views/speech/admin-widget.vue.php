<?php

use app\components\UrlHelper;

ob_start();
?>

<article class="speechAdmin">
    <div class="toolbarBelowTitle settings">
        <div class="settingsActive" v-if="queue.isActive">
            <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
            <?= Yii::t('speech', 'admin_is_active') ?>

            <button class="btn btn-xs btn-default" type="button" @click="setInactive()">
                <?= Yii::t('speech', 'admin_deactivate') ?>
            </button>
        </div>
        <div class="settingsActive" v-if="!queue.isActive">
            <span class="inactive"><?= Yii::t('speech', 'admin_is_inactive') ?></span>
            <button class="btn btn-xs btn-default" type="button" @click="setActive()">
                <?= Yii::t('speech', 'admin_activate') ?>
            </button>
            <span v-if="queue.otherActiveName" class="deactivateOthers">(<?= Yii::t('speech', 'admin_deactivate_other') ?>)</span>
        </div>
        <label class="settingsOpen" v-if="queue.isActive">
            <input type="checkbox" v-model="queue.settings.isOpen" @change="settingsChanged()">
            <?= Yii::t('speech', 'admin_setting_open') ?>
        </label>
        <div class="settingsPolicy">
            <div class="btn-group">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?= Yii::t('speech', 'admin_setting') ?>
                    <span class="caret" aria-hidden="true"></span>
                </button>
                <ul class="dropdown-menu">
                    <li class="checkbox">
                        <label @click="$event.stopPropagation()">
                            <input type="checkbox" class="preferNonspeaker" v-model="queue.settings.preferNonspeaker" @change="settingsChanged()">
                            <?= Yii::t('speech', 'admin_prefer_nonspeak') ?>
                        </label>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <main class="content">
        <section class="previousSpeakers" :class="{previousShown: showPreviousList, invisible: !hasPreviousSpeakers}">
            <header>
                <?= Yii::t('speech', 'admin_prev_speakers') ?>: {{ previousSpeakers.length }}

                <button class="btn btn-link" type="button" @click="showPreviousList = true" v-if="!showPreviousList">
                    <span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span>
                    <?= Yii::t('speech', 'admin_prev_show') ?>
                </button>
                <button class="btn btn-link" type="button" @click="showPreviousList = false" v-if="showPreviousList">
                    <span class="glyphicon glyphicon-chevron-up" aria-hidden="true"></span>
                    <?= Yii::t('speech', 'admin_prev_show') ?>
                </button>
            </header>

            <div class="previousLists" v-if="showPreviousList">
                <div class="previousList" v-for="subqueue in queue.subqueues">
                    <header v-if="queue.subqueues.length > 1 && subqueue.name !== 'default'"><span>{{ subqueue.name }}</span></header>
                    <header v-if="queue.subqueues.length > 1 && subqueue.name === 'default'"><span><?= Yii::t('speech', 'waiting_list_1') ?></span></header>
                    <ol>
                        <li v-for="item in getPreviousForSubqueue(subqueue)">
                            {{ item.name }}
                        </li>
                    </ol>
                </div>
            </div>
        </section>

        <ol class="slots" aria-label="<?= Yii::t('speech', 'speaking_list') ?>">
            <li v-if="activeSlot" class="slotEntry slotActive active">
                <span class="glyphicon glyphicon-comment iconBackground" aria-hidden="true"></span>

                <div class="status statusActive">
                    <?= Yii::t('speech', 'admin_running') ?>:
                </div>

                <div class="name">
                    {{ activeSlot.name }}
                </div>

                <button type="button" class="btn btn-danger stop" @click="stopSlot($event, activeSlot)">
                    <span class="glyphicon glyphicon-stop" title="<?= Yii::t('speech', 'admin_stop') ?>" aria-hidden="true"></span>
                    <span class="sr-only"><?= Yii::t('speech', 'admin_stop') ?></span>
                </button>

                <div class="operations">
                    <button type="button" class="link removeSlot" @click="removeSlot($event, activeSlot)" title="<?= Yii::t('speech', 'admin_back_to_wait') ?>">
                        <span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span>
                        <span class="sr-only"><?= Yii::t('speech', 'admin_back_to_wait') ?></span>
                    </button>
                </div>
            </li>
            <li v-if="!activeSlot" class="slotEntry slotActive inactive">
                <span class="glyphicon glyphicon-comment iconBackground" aria-hidden="true"></span>

                <div class="status statusActive">
                    <?= Yii::t('speech', 'admin_running') ?>:
                </div>

                <div class="nameNobody">
                    <?= Yii::t('speech', 'admin_running_nobody') ?>
                </div>
            </li>

            <li v-for="slot in sortedQueue" class="slotEntry">
                <span class="glyphicon glyphicon-time iconBackground" aria-hidden="true"></span>

                <div class="name">
                    {{ slot.name }}
                </div>

                <button type="button" class="btn btn-success start" @click="startSlot($event, slot)" title="<?= Yii::t('speech', 'admin_start') ?>">
                    <span class="glyphicon glyphicon-play" aria-hidden="true"></span>
                    <span class="sr-only"><?= Yii::t('speech', 'admin_start') ?></span>
                </button>

                <div class="operations">
                    <button type="button" class="link removeSlot" @click="removeSlot($event, slot)" title="<?= Yii::t('speech', 'admin_back_to_wait') ?>">
                        <span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span>
                        <span class="sr-only"><?= Yii::t('speech', 'admin_back_to_wait') ?></span>
                    </button>
                </div>
            </li>

            <li class="slotPlaceholder active" tabindex="0" v-if="slotProposal"
                @click="addItemToSlotsAndStart(slotProposal)"
                @keyup.enter="addItemToSlotsAndStart(slotProposal)">
                <span class="glyphicon glyphicon-time iconBackground" aria-hidden="true"></span>
                <div class="title"><?= Yii::t('speech', 'admin_next') ?>:</div>
                <div class="name">{{ slotProposal.name }}</div>
            </li>
            <li class="slotPlaceholder inactive" v-if="!slotProposal">
                <span class="glyphicon glyphicon-time iconBackground" aria-hidden="true"></span>
                <div class="title"><?= Yii::t('speech', 'admin_start_proposal') ?>:</div>
                <div class="nameNobody"><?= Yii::t('speech', 'admin_proposal_nobody') ?></div>
            </li>
        </ol>

        <div class="subqueues">
            <speech-admin-subqueue v-for="subqueue in queue.subqueues"
                                   :subqueue="subqueue"
                                   :allSubqueues="queue.subqueues"
                                   @add-item-to-slots-and-start="addItemToSlotsAndStart"
                                   @add-item-to-subqueue="addItemToSubqueue"
                                   @move-item-to-subqueue="moveItemToSubqueue"
            ></speech-admin-subqueue>
        </div>
    </main>
</article>

<?php
$html             = ob_get_clean();
$setStatusUrl     = UrlHelper::createUrl('speech/admin-setstatus');
$itemSetStatusUrl = UrlHelper::createUrl('speech/admin-item-setstatus');
$createItemUrl    = UrlHelper::createUrl('speech/admin-create-item');
$pollUrl          = UrlHelper::createUrl('speech/admin-poll');
?>

<script>
    Vue.component('speech-admin-widget', {
        template: <?= json_encode($html) ?>,
        props: ['queue', 'csrf'],
        data() {
            console.log(JSON.parse(JSON.stringify(this.queue)));
            return {
                showPreviousList: false,
                pollingId: null
            };
        },
        computed: {
            previousSpeakers: function () {
                return this.queue.slots.filter(function (slot) {
                    return slot.dateStopped !== null;
                }).sort(function (slot1, slot2) {
                    const date1 = new Date(slot1.dateStopped);
                    const date2 = new Date(slot2.dateStopped);
                    return date1.getTime() - date2.getTime();
                });
            },
            hasPreviousSpeakers: function () {
                return this.queue.slots.filter(function (slot) {
                    return slot.dateStopped !== null;
                }).length > 0;
            },
            sortedQueue: function () {
                return this.queue.slots.filter(function (slot) {
                    return slot.dateStarted === null;
                }).sort(function (slot1, slot2) {
                    return slot1.position - slot2.position;
                });
            },
            activeSlot: function () {
                const active = this.queue.slots.filter(function (slot) {
                    return slot.dateStarted !== null && slot.dateStopped === null;
                });
                return active.length > 0 ? active[0] : null;
            },
            upcomingSlot: function () {
                return this.sortedQueue.length > 0 ? this.sortedQueue[0] : null;
            },
            slotProposal: function () {
                const prevSpeakerNums = {};
                this.queue.slots.forEach(function (slot) {
                    if (prevSpeakerNums[slot.subqueue.id] === undefined) {
                        prevSpeakerNums[slot.subqueue.id] = 0;
                    }
                    prevSpeakerNums[slot.subqueue.id]++;
                });

                const queuesSortedByPreviousSpeakers = Object.assign([], this.queue.subqueues).sort(function (queue1, queue2) {
                    const num1 = (prevSpeakerNums[queue1.id] === undefined ? 0 : prevSpeakerNums[queue1.id]);
                    const num2 = (prevSpeakerNums[queue2.id] === undefined ? 0 : prevSpeakerNums[queue2.id]);
                    if (num1 === num2) {
                        return queue1.position - queue2.position; // First positions come first, if same amount of speakers
                    } else {
                        return num1 - num2; // Lower numbers first
                    }
                });

                // If queue no. 1 is empty and had less previous talkers than queue no. 2, we're not making a proposal

                const chosenQueue = queuesSortedByPreviousSpeakers[0];
                if (chosenQueue.applied.length === null) {
                    return null;
                }

                if (this.queue.settings.preferNonspeaker) {
                    // @TODO respect user ID + name
                    return chosenQueue.applied[0];
                } else {
                    return chosenQueue.applied[0];
                }
            }
        },
        methods: {
            _setStatus: function (id, op, additionalProps) {
                let postData = {
                    queue: this.queue.id,
                    item: id,
                    op,
                    _csrf: this.csrf,
                };
                if (additionalProps) {
                    postData = Object.assign(postData, additionalProps);
                }
                const widget = this;
                $.post(<?= json_encode($itemSetStatusUrl) ?>, postData, function (data) {
                    if (!data['success']) {
                        alert(data['message']);
                        return;
                    }

                    widget.queue = data['queue'];
                }).catch(function (err) {
                    alert(err.responseText);
                });
            },
            getPreviousForSubqueue: function (subqueue) {
                return this.previousSpeakers.filter(function (item) {
                    return item.subqueue.id === subqueue.id;
                });
            },
            startSlot: function ($event, slot) {
                $event.preventDefault();
                this._setStatus(slot.id, "start");
            },
            stopSlot: function ($event, slot) {
                $event.preventDefault();
                this._setStatus(slot.id, "stop");
            },
            removeSlot: function ($event, slot) {
                $event.preventDefault();
                this._setStatus(slot.id, "unset-slot");
            },
            // Not used currently
            addItemToSlots: function (item) {
                this._setStatus(item.id, "set-slot");
            },
            addItemToSlotsAndStart: function (item) {
                this._setStatus(item.id, "set-slot-and-start");
            },
            moveItemToSubqueue: function (item, newSubqueue) {
                this._setStatus(item.id, "move", {newSubqueueId: newSubqueue.id});
            },
            setInactive: function () {
              this.queue.isActive = false;
              this.settingsChanged();
            },
            setActive: function () {
              this.queue.isActive = true;
              this.settingsChanged();
            },
            settingsChanged: function () {
                const widget = this;
                $.post(<?= json_encode($setStatusUrl) ?>, {
                    queue: this.queue.id,
                    isActive: (this.queue.isActive ? 1 : 0),
                    isOpen: (this.queue.settings.isOpen ? 1 : 0),
                    preferNonspeaker: (this.queue.settings.preferNonspeaker ? 1 : 0),
                    _csrf: this.csrf,
                }, function (data) {
                    if (!data['success']) {
                        alert(data['message']);
                        return;
                    }

                    widget.queue = data['queue'];

                    if (data['sidebar'] && data['sidebar'][0] !== '') {
                        document.getElementById('sidebar').childNodes.item(0).innerHTML = data['sidebar'][0];
                        // @TODO Secondary sidebar
                    }
                }).catch(function (err) {
                    alert(err.responseText);
                });
            },
            addItemToSubqueue: function (subqueue, itemName) {
                const widget = this;
                $.post(<?= json_encode($createItemUrl) ?>, {
                    queue: this.queue.id,
                    subqueue: subqueue.id,
                    name: itemName,
                    _csrf: this.csrf,
                }, function (data) {
                    if (!data['success']) {
                        alert(data['message']);
                        return;
                    }

                    widget.queue = data['queue'];
                }).catch(function (err) {
                    alert(err.responseText);
                });
            },
            reloadData: function () {
                const widget = this;
                $.get(<?= json_encode($pollUrl) ?>, {queue: widget.queue.id}, function (data) {
                    if (!data['success']) {
                        return;
                    }
                    widget.queue = data['queue'];
                });
            },
            startPolling: function () {
                const widget = this;
                this.pollingId = window.setInterval(function () {
                    widget.reloadData();
                }, 3000);
            }
        },
        beforeDestroy() {
            window.clearInterval(this.pollingId)
        },
        created() {
            this.startPolling()
        }
    });
</script>
