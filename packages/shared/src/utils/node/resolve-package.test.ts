import { resolvePackage } from './resolve-package';
import { dirSync } from 'tmp';
import { ensureDirSync, writeJsonSync } from 'fs-extra';
import path from 'path';
describe('', () => {
	let rootDir: any;
	beforeEach(() => {
		rootDir = dirSync({ unsafeCleanup: true, tmpdir: './' } as any);
		ensureDirSync(`${rootDir.name}/node_modules/`);
		ensureDirSync(`${rootDir.name}/node_modules/test-package/`);
		writeJsonSync(`${rootDir.name}/node_modules/test-package/package.json`, { name: 'test' });
	});

	afterEach(() => {
		rootDir.removeCallback();
	});
	it('the package to be found', () => {
		expect(resolvePackage('test-package', rootDir.name)).toBe(
			path.resolve(`${rootDir.name}/node_modules/test-package`)
		);
	});
});
