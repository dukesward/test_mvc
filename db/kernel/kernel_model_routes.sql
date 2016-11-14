replace into kernel_model_routes (name, pattern, controller, action, params, defaults)
values
('default', null, 'index', 'index', null, null),
('exception', null, 'exception', 'index', null, null),
('flashcard', 'flashcard/', 'flash_card', 'index', null, null),
('staticcontent', 'staticcontent/', 'static', 'index', null, null),
('kingdom', 'kingdom/', 'kingdom', 'index', null, null),
('mall', 'mall/', 'mall', 'index', null, null);