var configs = {
	'damage_types': {
		'p': 'physical',
		'h': 'heal'
	},
	'attrs': {
		'threat_basic': {
			'overlay': 'multiply'
		}
	},
	'states': {
		'bleeding': {
			'sub_type': 'damage',
			'attr': 'physical',
			'alt': '流血'
		},
		'threat':   {
			'sub_type': 'buff',
			'modify': 'threat_basic',
			'alt': '威胁提升'
		},
		'absorption': {
			'sub_type': 'buff',
			'modify': 'absorption',
			'alt': '获得吸收',
			'effect': '1'
		}
	},
	'conditions': {
		'protection': {
			'targets': ['tank','self'],
			'each': {
				'tank': [{
					'type': 'hp_<',
					'value': '0.75'
				}],
				'self': [{
					'type': 'hp_<',
					'value': '1'
				}]
			}
		}
	}
}