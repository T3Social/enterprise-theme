humhub.module('ui.theme', function (module, require, $) {
    module.export({
        getContentTop: function() {
            var $topBar = $('.space-nav:first');

            if(!$topBar.length) {
                $topBar = $('#topbar-first');
            }

            return $topBar.offset().top + $topBar.height() - $(window).scrollTop();
        }
    });
});

humhub.module('enterprise.theme', function (module, require, $) {
    var event = require('event');
    var object = require('util.object');
    var additions = require('ui.additions');
    var view = require('ui.view');

    event.on('humhub:modules:space:chooser:beforeInit', function (evt, spaceChooser) {
        var SpaceChooser = spaceChooser.SpaceChooser;

        SpaceChooser.prototype.init = function () {
            this.$menu = $('#space-menu-dropdown');
            this.$chooser = $('#space-menu-remote-search');
            this.$search = $('#space-menu-search');
            this.$remoteSearch = $('#space-menu-remote-search');

            this.initEvents();
            this.initSpaceSearch();

            var that = this;
            this.on('changed', function(evt, input) {
                if(input && input.length) {
                    that.disableShowMore();
                }
            }).on('resetSearch', function() {
                that.enableShowMore();
            });
        };

        SpaceChooser.prototype.setSpace = function (space) {
            this.getItems().removeClass('active');
            this.findItem(space).addClass('active');
            this.setSpaceMessageCount(space, 0);
        };

        SpaceChooser.prototype.setNoSpace = function () {
            this.getItems().removeClass('active');
        };

        SpaceChooser.prototype.getFirstItem = function () {
            return this.$.find('[data-space-chooser-item]:visible').first();
        };

        SpaceChooser.prototype.hasItems = function () {
            return this.$.find('[data-space-chooser-item]').length > 0;
        };

        SpaceChooser.prototype.prependItem = function (space) {
            if (!this.findItem(space).length) {
                var $space = $(space.output);
                var spaceTypeId = $space.data('space-type');
                var $menu = (object.isNumber(spaceTypeId)) ? $('#space-menu-type-' + spaceTypeId) : this.$chooser;

                if (!$menu.length) {
                    $menu = this.$chooser;
                }

                $menu.prepend($space);
                additions.applyTo($space);
            }
        };

        SpaceChooser.prototype.appendItem = function (space) {
            if (!this.findItem(space).length) {
                var $space = $(space.output);
                var spaceTypeId = $space.data('space-type');
                var $menu = (object.isNumber(spaceTypeId)) ? $('#space-menu-type-' + spaceTypeId) : this.$chooser;

                if (!$menu.length) {
                    $menu = this.$chooser;
                }

                $menu.append($space);
                additions.applyTo($space);
            }
        };

        SpaceChooser.prototype.clearRemoteSearch = function (input) {
            // Clear all non member and non following spaces
            this.$.find('[data-space-none],[data-space-archived]').each(function () {
                var $this = $(this);
                if (!input || !input.length || $this.find('.space-name').text().toLowerCase().search(input) < 0) {
                    $this.remove();
                }
            });
        };

        SpaceChooser.prototype.toggleShowMore = function (evt) {
            var $this = evt.$trigger;
            var $hiddenItems = $this.parents('.space-entries').find('.hidden-chooser-item');
            var $i = $this.find('i');
            var $span = $this.find('span');
            $hiddenItems.slideToggle('fast');
            $i.toggleClass('fa-angle-down').toggleClass('fa-angle-up');
            if ($i.hasClass('fa-angle-up')) {
                $span.text($span.data('show-less'));
            } else {
                $span.text($span.data('show-more'));
            }
        };

        SpaceChooser.prototype.disableShowMore = function (showItems) {
            $('.space-type-visibility-control').each(function() {
                var $this = $(this);
                if(showItems) {
                    var $hiddenItems = $this.parents('.space-entries').find('.hidden-chooser-item').show();
                }
                $this.hide();
            });
        };

        SpaceChooser.prototype.enableShowMore = function () {
            $('.space-type-visibility-control').each(function() {
                var $this = $(this);
                $this.parents('.space-entries').find('.hidden-chooser-item').hide();
                $this.find('i').removeClass('fa-angle-up').addClass('fa-angle-down');

                var $span = $this.find('span');
                $span.text($span.data('show-more'));
                $this.show();
            });
        };
    });

    module.initOnPjaxLoad = true;

    var init = function (pjax) {
        _closeMenu();

        setTimeout(_alignSpaceNavTop, 500);

        $( window ).resize(function() {
            _alignSpaceNavTop();
        });

        if (!pjax) {
            _initTopMenuToggle();
            _initSpaceTypeNav();
        }
    };

    var _initSpaceTypeNav = function () {
        var mq = window.matchMedia("(max-width: 768px)");

        if (!mq.matches) {
            _addNiceScroll();

        }

        $('.space-type-nav-title').on('click', function () {
            var $this = $(this);
            $this.next('li').find('.space-type-nav-container').slideToggle('fast');
            $this.find('i').toggleClass('fa-caret-down').toggleClass('fa-caret-up');
        });

        $('.title-link').on('click', function (evt) {
            // prevent trigger of space-type-nav-title
            evt.stopPropagation();
        });
    };

    var _alignSpaceNavTop = function () {
        _handleSpaceNavOverflow();
       /* var navHeight = require('ui.theme').getContentTop() - parseInt($('#page-content-wrapper').css('padding-top'));
        $('.space-layout-container').animate({'margin-top': navHeight + 20});*/
    };

    var _handleSpaceNavOverflow = function() {
        var $spaceDetails = $('.space-nav .space-details');
        var $spaceNav = $spaceDetails.siblings('.navbar-nav');
        var $headercontrols = $spaceDetails.siblings('.space-nav-buttons');
        var $spaceSubmenuDropdown = $spaceNav.find('#top-menu-sub-dropdown');

        if(!$spaceSubmenuDropdown.length || !$spaceDetails.length && !$spaceNav.length || !$headercontrols.length) {
            return;
        }

        if($spaceDetails.offset().top < $headercontrols.offset().top) {
            $spaceNav.find('#top-menu-sub').show();
        }

        var counter = 0;
        while($spaceDetails.offset().top < $headercontrols.offset().top && counter < 50) {
            var $listItem = $spaceNav.find('li:visible:not(#top-menu-sub):last');
            if(!$listItem.length) {
               break;
            }

            $spaceSubmenuDropdown.prepend($listItem);
            counter++;
        }

        if(!$spaceSubmenuDropdown.find('li').length) {
            $spaceNav.find('#top-menu-sub').hide();
        }
    };

    var _addNiceScroll = function () {
        $("#sidebar-wrapper").niceScroll({
            cursorwidth: '7',
            cursorborder: '',
            cursorcolor: '#606572',
            cursoropacitymax: '0.3',
            nativeparentscrolling: false,
            railpadding: {top: 0, right: 3, left: 0, bottom: 0}
        });
    };

    var _removeNiceScroll = function () {
        $("#sidebar-wrapper").getNiceScroll().remove();
    };

    var _resizeNiceScroll = function () {
        $("#sidebar-wrapper").getNiceScroll().resize();
    };

    var _initTopMenuToggle = function () {

        $(document).on('swiped-right.enterprise', function(e) {
            if(view.isActiveScroll && view.isActiveScroll()) {
                return;
            }

            var $sidebar = $('.layout-sidebar-container');
            if(!$('#rsp-backdrop').length && (!$sidebar.length || !$sidebar.is(':visible'))) {
                $(".menu-toggle:first").trigger('click');
            }
        });

        $(document).on('swiped-left.enterprise', function(e) {
            if(view.isActiveScroll && view.isActiveScroll()) {
                return;
            }

            if($('#rsp-backdrop').length) {
                e.preventDefault();
                $(".menu-toggle:first").trigger('click');
            }
        });

        var mq = window.matchMedia("(max-width: 768px)");

        $(".menu-toggle").click(function (e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");

            if (!$('#wrapper').hasClass('toggled')) {
                $('#rp-nav').css('display', 'block');
                $('#topbar-first').css('padding-left', '0');
                $('#topbar-first div').removeClass('hidden');
                $('.space-nav .nav').removeClass('hidden');
                $('#rsp-backdrop').remove();

                if (mq.matches) {
                    $('#sidebar-wrapper').css('touch-action', '');

                    if(view.preventSwipe) {
                        setTimeout(function() {
                            view.preventSwipe(false);
                        },500);
                    }
                } else {
                    _removeNiceScroll();
                }
            } else {
                $('#rp-nav').css('display', 'none');
                $('#topbar-first').css('padding-left', '250px');

                if (mq.matches) {
                    $('#topbar-first div').addClass('hidden');
                    $('.space-nav .nav').addClass('hidden');

                    $('#page-content-wrapper').append('<div id="rsp-backdrop" class="modal-backdrop in" style="z-index: 940;"></div>');
                    $('#sidebar-wrapper, #rsp-backdrop').css('touch-action', 'pan-y');

                    if(view.preventSwipe) {
                        view.preventSwipe();
                    }
                } else {
                    _addNiceScroll();
                    setTimeout(_resizeNiceScroll, 300)
                }
            }
        });
    };

    var _closeMenu = function () {
        var mq = window.matchMedia("(max-width: 768px)");

        $("#wrapper").removeClass("toggled");


        $('#rp-nav').css('display', 'block');
        $('#topbar-first').css('padding-left', '0');

        if (mq.matches) {
            $('#topbar-first div').removeClass('hidden');
            $('.space-nav .nav').removeClass('hidden');
            $('#rsp-backdrop').remove();
        }
    };

    module.export({
        init: init
    });
});
