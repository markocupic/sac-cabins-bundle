"use strict";

/**
 * SAC Event Tool Web Plugin for Contao
 * Copyright (c) 2008-2020 Marko Cupic
 * @package sac-event-tool-bundle
 * @author Marko Cupic m.cupic@gmx.ch, 2017-2020
 * @link https://github.com/markocupic/sac-event-tool-bundle
 */
class VueTourList {

    constructor(elId, params) {

        new Vue({
            el: elId,
            created: function created() {
                var self = this;

                self.prepareRequest(false);
            },

            data: function data() {
                return {

                    // Load x items per request
                    limitPerRequest: params.limitPerRequest,
                    // Limit total results
                    limitTotal: params.limitTotal,
                    // The frontend module id
                    moduleId: params.moduleId,
                    // Calendar ids
                    calendarIds: params.calendarIds,
                    // Image size array
                    imgSize: params.imgSize,
                    // Picture id
                    pictureId: params.pictureId,
                    // Event types array
                    eventTypes: params.eventTypes,
                    // Filter param array base64 encoded
                    filterParam: params.filterParam,
                    // Endpoint url
                    ajaxEndpoint: params.ajaxEndpoint,
                    // Contao request token
                    requestToken: params.requestToken,
                    // Fields array
                    fields: params.fields,
                    // Result row
                    rows: [],
                    // Requested event ids
                    arrIds: null,
                    // is busy bool
                    blnIsBusy: false,
                    // total found items
                    itemsFound: 0,
                    // already loaded items
                    loadedItems: 0,
                    // all events loades bool
                    blnAllEventsLoaded: false,
                };
            },
            methods: {
                // Prepare ajax request
                prepareRequest: function prepareRequest(isPreloadRequest = false) {
                    var self = this;

                    if (self.blnIsBusy === false) {
                        self.blnIsBusy = true;
                        self.getDataByXhr(isPreloadRequest);
                        console.log('Loading events...')
                    }
                },

                // Preload and use the session cache
                preload: function preload() {
                    var self = this;
                    self.prepareRequest(true);
                },

                // Get data by xhr
                getDataByXhr: function getDataByXhr(isPreloadRequest) {
                    var self = this;
                    var counter = 0;
                    var limitPerRequest = self.limitPerRequest;

                    if (self.blnAllEventsLoaded === true) {
                        return;
                    }

                    let data = new FormData();
                    data.append('REQUEST_TOKEN', self.requestToken);
                    data.append('offset', self.loadedItems);
                    data.append('limitPerRequest', self.limitPerRequest);
                    data.append('limitTotal', self.limitTotal);
                    data.append('moduleId', self.moduleId);
                    data.append('imgSize', self.imgSize);
                    data.append('pictureId', self.pictureId);
                    data.append('calendarIds', self.calendarIds);
                    data.append('eventTypes', self.eventTypes);
                    data.append('ajaxEndpoint', self.ajaxEndpoint);
                    data.append('requestToken', self.requestToken);
                    data.append('fields', btoa(self.fields));
                    data.append('arrIds', btoa(self.arrIds));
                    data.append('filterParam', self.filterParam);
                    data.append('sessionCacheToken', btoa(window.location.href));
                    data.append('isPreloadRequest', isPreloadRequest);

                    // Fetch
                    fetch(self.ajaxEndpoint, {

                            method: "POST",
                            body: data,
                            headers: {
                                'x-requested-with': 'XMLHttpRequest'
                            },
                        }
                    ).then(function (res) {
                        return res.json();
                    }).then(function (data) {

                        console.log(data);
                        self.blnIsBusy = false;

                        let i = 0;
                        self.itemsFound = data['itemsFound'];
                        data['arrEventData'].forEach(function (row) {
                            i++;
                            self.rows.push(row);
                            self.loadedItems++;
                        });

                        // Get ids to speed up requests
                        self.arrIds = data['arrIds'];

                        if (data['isPreloadRequest'] === false) {
                            if (i === 0 || parseInt(data['itemsFound']) === self.loadedItems) {
                                self.blnAllEventsLoaded = true
                            }
                        }

                        window.setTimeout(function () {
                            $(self.$el).find('[data-toggle="tooltip"]').tooltip();
                        }, 100);

                        if (self.blnAllEventsLoaded === true) {
                            console.log('Finished downloading process. ' + self.loadedItems + ' events loaded.');
                        } else {
                            if (data['isPreloadRequest'] === false) {
                                // Preload
                                self.preload();
                            }
                        }
                        return json;

                    });
                }
            }
        });
    }
}
