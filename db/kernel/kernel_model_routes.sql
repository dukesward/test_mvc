replace into kernel_model_routes (name, pattern, controller, action, params, defaults)
values
('default', null, 'index', 'index', null, null),
('exception', null, 'exception', 'index', null, null),
('flashcard', 'flashcard/', 'flash_card', 'index', null, null);