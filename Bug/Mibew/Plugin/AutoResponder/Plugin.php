<?php
 
/**
 * @file The main file of Bug:AutoResponder plugin.
 *
 * Plugin's class name, its namespace and file path must follow special naming conventions.
 * The name of the main plugin class should match pattern:
 * "\<Vendor Name>\Mibew\Plugin\<Plugin name>\Plugin".
 * It should be placed in "<mibew root>/plugins/<Vendor Name>/Mibew/Plugin/<Plugin name>/Plugin.php" file.
 * Names of plugin and directories/files are case sensitive!
 * 
 * To turn the plugin on add the following to <mibew root>/libs/config.php
 * <code>
 * $plugins_list[] = array(
 *  'name' => 'bug:AutoResponder',
 *  'config' => array();
 * );
 * </code>
 */
 
namespace Bug\Mibew\Plugin\AutoResponder;

// Plugin class must implements \Mibew\Plugin\PluginInterface and can extends
// \Mibew\Plugin\AbstractPlugin class. The latter contains basic functions that can be helpfull
class Plugin extends \Mibew\Plugin\AbstractPlugin implements \Mibew\Plugin\PluginInterface
{
 
    /**
     * Determine if the plugin was initialized correctly or not.
     *
     * By setting this propery to true by default we make the plugin
     * initialized by default, so there is no need to add custom contructor
     * or initializer.
     */
    protected $initialized = true;
 
    /**
     * The main entry point of a plugin
     *
     * If a plugin extends \Mibew\Plugin\AbstructPlugin class the only method
     * that should be implemented is "run".
     *
     * Here you can attache event listeners and do other job.
     */
    public function run()
    {
        $dispatcher = \Mibew\EventDispatcher::getInstance();
        $dispatcher->attachListener('threadFunctionCall',
            $this,
            'callFunctionListener');
    }
        
    /**
     * This is the example of EventListener for threadFunctionCall event
     *
     * all eventListeners have &$Arg argument
     * with modiffing &$Arg array you can do your job and add changes to MIBEW
     */
    public function callFunctionListener(&$function)
    {
        // Check what function was called from the client side.
        // the post function used for message sent from chatbox
        if ($function['function'] == 'post') {
            // Someone post the message in the thread. Check who did it.
            if (!$function['arguments']['user']) {
                // The message was sent by operator. There is no need to
                // continue.
                return;
            }
 
            // Check if we have thread ID
            $thread_id = $function['arguments']['threadId'];
            if (!$thread_id) {
                // Something went wrong. Client side has not passed id of the
                // thread. Just return.
                return;
            }
 
            // Load the thread
            $thread = \Mibew\Thread::load($thread_id);
            if (!$thread) {
                // Something went wrong. We cannot load the thread.
                return;
            }
            
            // Check for thread state to solve issu #2
            if ($thread->state != $thread::STATE_CLOSED &&  
                $thread->state != $thread::STATE_QUEUE) {
                // No nead to autoresponder based on Thread_State
                return;
            }
            
            // Send a simple message for user.
            $thread->postMessage(
                \Mibew\Thread::KIND_INFO,
                'Just wait for a while. An operator will answer you soon.'
            );
        }
    }
}
